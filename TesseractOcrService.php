[1mdiff --git a/app/Services/TesseractOcrService.php b/app/Services/TesseractOcrService.php[m
[1mindex ffcbdc4..3355195 100644[m
[1m--- a/app/Services/TesseractOcrService.php[m
[1m+++ b/app/Services/TesseractOcrService.php[m
[36m@@ -552,6 +552,25 @@[m [mprivate function extraerMonto(array $cleanLines, array $rawLines): ?float[m
         $normalizedMap[] = $normalized;[m
     }[m
 [m
[32m+[m[32m    Log::debug('MontoOCR: DEBUG líneas candidatas para extraer monto', [[m
[32m+[m[32m        'raw'   => array_values(array_filter([m
[32m+[m[32m            $rawLines,[m
[32m+[m[32m            fn($l) => (bool) preg_match('/[S5s\$][\/\:\|I1l]?/u', $l)[m
[32m+[m[32m                || stripos($l, 'monto') !== false[m
[32m+[m[32m                || stripos($l, 'total') !== false[m
[32m+[m[32m                || stripos($l, 'importe') !== false[m
[32m+[m[32m                || stripos($l, 'pago') !== false[m
[32m+[m[32m        )),[m
[32m+[m[32m        'clean' => array_values(array_filter([m
[32m+[m[32m            $normalizedMap,[m
[32m+[m[32m            fn($l) => (bool) preg_match('/[S5s\$][\/\:\|I1l]?/u', $l)[m
[32m+[m[32m                || stripos($l, 'monto') !== false[m
[32m+[m[32m                || stripos($l, 'total') !== false[m
[32m+[m[32m                || stripos($l, 'importe') !== false[m
[32m+[m[32m                || stripos($l, 'pago') !== false[m
[32m+[m[32m        )),[m
[32m+[m[32m    ]);[m
[32m+[m
     /*[m
      * 1. Formato principal:[m
      *    S/1[m
[36m@@ -582,6 +601,11 @@[m [mprivate function extraerMonto(array $cleanLines, array $rawLines): ?float[m
                     && (float) $val > 0[m
                     && (float) $val < 100000[m
                 ) {[m
[32m+[m[32m                    Log::info('MontoOCR: DEBUG monto por patrón principal', [[m
[32m+[m[32m                        'linea'   => $line,[m
[32m+[m[32m                        'captura' => $m[1],[m
[32m+[m[32m                        'monto'   => (float) $val,[m
[32m+[m[32m                    ]);[m
                     return (float) $val;[m
                 }[m
             }[m
[36m@@ -607,6 +631,11 @@[m [mprivate function extraerMonto(array $cleanLines, array $rawLines): ?float[m
                 && (float) $val > 0[m
                 && (float) $val < 100000[m
             ) {[m
[32m+[m[32m                Log::info('MontoOCR: DEBUG monto por patrón standalone', [[m
[32m+[m[32m                    'linea'   => trim($line),[m
[32m+[m[32m                    'captura' => $m[1],[m
[32m+[m[32m                    'monto'   => (float) $val,[m
[32m+[m[32m                ]);[m
                 return (float) $val;[m
             }[m
         }[m
[36m@@ -632,6 +661,11 @@[m [mprivate function extraerMonto(array $cleanLines, array $rawLines): ?float[m
                 && (float) $val < 100000[m
                 && !str_contains($line, ':')[m
             ) {[m
[32m+[m[32m                Log::info('MontoOCR: DEBUG monto por patrón genérico', [[m
[32m+[m[32m                    'linea'   => $line,[m
[32m+[m[32m                    'captura' => $m[1],[m
[32m+[m[32m                    'monto'   => (float) $val,[m
[32m+[m[32m                ]);[m
                 return (float) $val;[m
             }[m
         }[m
