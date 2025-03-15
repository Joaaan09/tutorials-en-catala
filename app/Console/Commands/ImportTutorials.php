<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\IFixitService;
use App\Models\Tutorial;
use App\Models\Step;
use App\Models\Image;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Facades\Log;

class ImportTutorials extends Command
{
    protected $signature = 'import:tutorials';
    protected $description = 'Import tutorials from iFixit API';

    public function handle()
    {
        $service = new IFixitService();
        $translator = new GoogleTranslate('ca');

        // Filtrar y limitar tutoriales
        $tutorials = array_slice($service->getTutorials('Electronics'), 6, 5);

        foreach ($tutorials as $guide) {
            $details = $service->getTutorialDetails($guide['guideid']);

            // Traducción del título y descripción
            $translatedTitle = $this->translateText($translator, $guide['title']);
            $translatedDescription = $this->translateText($translator, $guide['summary'] ?? $guide['description'] ?? '');

            $tutorial = Tutorial::create([
                'title' => $translatedTitle,
                'description' => $translatedDescription,
                'category' => $guide['category'],
                'original_content' => json_encode($guide)
            ]);

            // Verificar si hay pasos
            if (empty($details['steps'])) {
                Log::warning("No hi ha passos per al tutorial: {$guide['guideid']}");
                continue;
            }

            // Procesar pasos
            foreach ($details['steps'] as $step) {
                Log::info("Procesando paso - Guide ID: {$guide['guideid']}", ['step' => $step]);

                // 1. Extraer TODAS las líneas de texto
                $originalText = '';
                if (!empty($step['lines'])) {
                    foreach ($step['lines'] as $line) {
                        if (!empty($line['text_raw'])) {
                            $originalText .= $line['text_raw'] . "\n";
                        }
                    }
                    $originalText = trim($originalText);
                }

                // 2. Fallback para texto
                if (empty($originalText)) {
                    $originalText = $step['text'] ?? $step['title'] ?? $step['description'] ?? '';
                }

                // 3. Manejar texto vacío
                if (trim($originalText) === '') {
                    Log::warning("Texto original vacío", [
                        'guide_id' => $guide['guideid'],
                        'step_id' => $step['stepid']
                    ]);
                    $originalText = '[Sense text original]';
                    $translatedInstructions = '[Text no disponible]';
                } else {
                    try {
                        $translatedInstructions = $this->translateText($translator, $originalText);
                    } catch (\Exception $e) {
                        Log::error("Error traduciendo paso: " . $e->getMessage());
                        $translatedInstructions = '[Error de traducció]';
                    }
                }

                // 4. Crear paso y manejar imágenes EN EL MISMO BLOQUE TRY
                try {
                    // Crear el paso
                    $stepModel = Step::create([
                        'tutorial_id' => $tutorial->id,
                        'instructions' => $originalText,
                        'translated_instructions' => $translatedInstructions,
                        'order' => $step['order'] ?? $step['stepid'] ?? 0
                    ]);

                    Log::info("Paso creado exitosamente - ID: {$stepModel->id}");

                    // Manejar imágenes (DENTRO DEL TRY)
                    if (isset($step['media']['data'])) {
                        Log::info("Processant imatges per al pas: {$step['stepid']}");
                        foreach ($step['media']['data'] as $image) {
                            $imageUrl = $image['original'] ?? $image['standard'] ?? $image['thumbnail'] ?? 'https://placehold.co/600x400';
                            
                            Image::create([
                                'step_id' => $stepModel->id,
                                'url' => $imageUrl
                            ]);
                        }
                    } else {
                        Log::warning("No hi ha imatges per al pas: {$step['stepid']}");
                    }

                } catch (\Exception $e) {
                    Log::error("Error creando paso: " . $e->getMessage());
                    continue;
                }

                // Delay fuera del try-catch
                sleep(2);
            }

            $this->info("Tutorial importat: {$translatedTitle}");
        }
    }

    protected function translateText($translator, $text)
    {
        if (empty($text)) {
            return '[Text no disponible]';
        }

        try {
            Log::info("Traduint: {$text}");
            $translatedText = $translator->translate($text);
            Log::info("Traducció exitosa: {$translatedText}");
            return $translatedText;
        } catch (\Exception $e) {
            Log::error("Error traduint: " . $e->getMessage());
            sleep(10);
            return '[Error en traducció]';
        }
    }
}