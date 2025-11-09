<?php

namespace App\Support;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class HabilProfValidator
{
    // ========== REGLAS BÁSICAS ==========
    // R1.1 y R1.3: RUT ∈ [1.000.000, 99.999.999], 7–8 dígitos
    public static function reglaRut(): array
    {
        return ['required','integer','between:1000000,99999999'];
    }

    // R1.2 y R1.4: Nombre: letras + espacios + guiones (no al inicio/fin de cada substring)
    // tamaño 13–100, con capitalización posterior (opcional)
    public static function reglaNombre(): array
    {
        return [
            'required',
            'string',
            'min:13',
            'max:100',
            // permite palabras con letras y guiones internos, separadas por espacio
            // no permite espacios/guiones repetidos ni al inicio/fin total
            'regex:/^(?!.* {2,})(?!.*--)(?![ -])(?!.*[ -]$)(?:[A-Za-z]+(?:-[A-Za-z]+)?)(?: (?:[A-Za-z]+(?:-[A-Za-z]+)?))*$/'
        ];
    }

    // R1.5: Semestre AAAA-Y con AAAA∈[2025,2045], Y∈{1,2}
    public static function reglaSemestre(): array
    {
        return ['required','regex:/^(2025|2026|2027|2028|2029|2030|2031|2032|2033|2034|2035|2036|2037|2038|2039|2040|2041|2042|2043|2044|2045)-(1|2)$/'];
    }

    // R1.6: Nota_Final Float [1.0, 7.0] con 1 decimal; admite NULL
    public static function reglaNotaFinal(): array
    {
        return ['nullable','regex:/^(?:[1-6]\.[0-9]|7\.0)$/'];
        // (Opcional) además del regex, abajo validamos rango numérico exacto
    }

    // R1.7 y R1.8: Fecha DD/MM/AAAA con AAAA∈[2025,2045]
    public static function reglaFecha(): array
    {
        return ['nullable','regex:/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/(2025|2026|2027|2028|2029|2030|2031|2032|2033|2034|2035|2036|2037|2038|2039|2040|2041|2042|2043|2044|2045)$/'];
    }

    // ========== VALIDACIONES COMPUESTAS POR CASO ==========

    // Alumno (R1.1, R1.2, R1.5)
    public static function validarAlumno(array $d): array
    {
        $v = Validator::make($d, [
            'RUT_Alumno'     => self::reglaRut(),
            'Nombre_Alumno'  => self::reglaNombre(),
            'Semestre_Inicio'=> self::reglaSemestre(),
        ]);

        self::afterCapitalizarNombre($v, $d, 'Nombre_Alumno');
        return self::resultado($v);
    }

    // Profesor (R1.3, R1.4)
    public static function validarProfesor(array $d): array
    {
        $v = Validator::make($d, [
            'RUT_Profesor'    => self::reglaRut(),
            'Nombre_Profesor' => self::reglaNombre(),
        ]);

        self::afterCapitalizarNombre($v, $d, 'Nombre_Profesor');
        return self::resultado($v);
    }

    // Habilitación + Nota/Fechas (R1.5, R1.6, R1.7, R1.8, R1.10)
    // Nota_Final y Fecha_Nota son opcionales; si hay Nota_Final debe tener 1 decimal y rango válido
    public static function validarHabilitacion(array $d): array
    {
        $v = Validator::make($d, [
            'Semestre_Inicio' => self::reglaSemestre(),
            'Nota_Final'      => self::reglaNotaFinal(), // nullable
            'Fecha_Inicio'    => array_merge(self::reglaFecha(), ['required']), // R1.7 es obligatoria
            'Fecha_Nota'      => self::reglaFecha(), // opcional (R1.10)
        ]);

        $v->after(function($v) use ($d) {
            // Nota: rango exacto y 1 decimal
            if (isset($d['Nota_Final']) && $d['Nota_Final'] !== null && $d['Nota_Final'] !== '') {
                $val = (float)$d['Nota_Final'];
                // 1 decimal exacto
                if (round($val,1) != $val) {
                    $v->errors()->add('Nota_Final','Nota_Final debe tener exactamente 1 decimal.');
                }
                if ($val < 1.0 || $val > 7.0) {
                    $v->errors()->add('Nota_Final','Nota_Final debe estar en [1.0, 7.0].');
                }
                // si hay Nota_Final, Fecha_Nota puede venir; si viene, la validación de formato ya la revisa
            }

            // Fechas: validar calendario real (31/02 inválido, etc.)
            foreach (['Fecha_Inicio','Fecha_Nota'] as $campo) {
                if (!empty($d[$campo])) {
                    $dt = Carbon::createFromFormat('d/m/Y', $d[$campo]);
                    if (!$dt || $dt->format('d/m/Y') !== $d[$campo]) {
                        $v->errors()->add($campo,"$campo no es una fecha válida (DD/MM/AAAA).");
                    } else {
                        $y = (int)$dt->format('Y');
                        if ($y < 2025 || $y > 2045) {
                            $v->errors()->add($campo,"$campo debe tener año entre 2025 y 2045.");
                        }
                    }
                }
            }
        });

        return self::resultado($v);
    }

    // ========== HELPERS ==========

    // Aplica capitalización tipo Título a nombre (opcional)
    protected static function afterCapitalizarNombre($validator, array $d, string $key): void
    {
        $validator->after(function($v) use ($d, $key) {
            if (!empty($d[$key])) {
                // Capitaliza palabras y segmentos con guión: "juan pérez-garcía" -> "Juan Pérez-García"
                $nombre = trim($d[$key]);
                $nombre = implode(' ', array_map(function($pal){
                    return implode('-', array_map(fn($seg)=>mb_convert_case($seg, MB_CASE_TITLE, "UTF-8"), explode('-', $pal)));
                }, preg_split('/\s+/', $nombre)));
                // No marcamos error; si quieres, puedes devolver el nombre normalizado en tu flujo
            }
        });
    }

    protected static function resultado($validator): array
    {
        if ($validator->fails()) {
            return ['ok'=>false, 'errors'=>$validator->errors()->toArray()];
        }
        return ['ok'=>true, 'errors'=>[]];
    }

    // R1.12: Generar ID_Habilitacion = RUT_Alumno + AAAAY (como número entero)
    public static function generarIdHabilitacion(int $rutAlumno, string $semestreInicio): int
    {
        // semestre: "AAAA-Y" -> AAAA + Y
        [$aaaa, $y] = explode('-', $semestreInicio);

        // Concatenamos rut + año + semestre
        $id = $rutAlumno . $aaaa . $y;

        // Retorna como entero (compatible con BIGINT)
        return (int) $id;
    }

}
