<?php

namespace Core;

class Utils
{

    static function iconFromExtension($extension) {
        if (empty($extension)) {
            return 'folder';
        }

        $iconMap = [
            '(doc|docm|docx|odt)' => 'file-word',
            '(potx|pptx)' => 'file-powerpoint',
            '(ods|xls|xlsx|xml)' => 'file-excel',
            '(csv)' => 'file-csv',
            '(pdf)' => 'file-pdf',
            '(webm|mkv|flv|wmv|avi|mp4|m4p|m4v|mpg|mpeg|mpv)' => 'file-video',
            '(jpeg|jpg|png|gif|tiff|raw)' => 'file-image',
            '(7z|rar|zip|tar|tar.gz)' => 'file-archive',
        ];

        foreach ($iconMap as $regex => $iconT) {
            if (preg_match($regex, $extension)) {
                return $iconT;
            }
        }

        return 'file';
    }

    static function humanizeBytes($size) {
        $_sz = 'BKMGTP';
        $_factor = floor((strlen($size) - 1) / 3);
        $humanSize = sprintf("%.1f ", $size / pow(1024, $_factor)) . @$_sz[$_factor];

        if ($humanSize !== 'B') {
            $humanSize .= 'B';
        }

        return $humanSize;
    }

    static function humanizeDateDifference($now, $otherDate = null, $offset = null)
    {
        if ($otherDate !== null) {
            $offset = $now - $otherDate;
        }

        if ($offset === null) {
            throw new \Exception("Must supply other date or offset (from now)");
        }

        $deltaS = $offset % 60;
        $offset /= 60;
        $deltaM = $offset % 60;
        $offset /= 60;
        $deltaH = $offset % 24;
        $offset /= 24;
        $deltaD = ($offset > 1) ? ceil($offset) : $offset;

        if ($deltaD > 364) {
            $years = ceil($deltaD / 365);
            if ($years == 1) {
                return "há um ano";
            } else {
                return "há $years anos";
            }
        }

        if ($deltaD > 29) {
            $months = ceil($deltaD / 30);
            if ($months == 1) {
                return "há um mês";
            } else {
                return "há $months meses";
            }
        }

        if ($deltaD > 6) {
            $weeks = ceil($deltaD / 7);
            if ($weeks == 1) {
                return "há uma semana";
            } else {
                return "há $weeks semanas";
            }
        }

        if ($deltaD > 1) {
            return "há $deltaD dias";
        }

        if ($deltaD == 1) {
            return "ontem";
        }

        if ($deltaH > 1) {
            return "há $deltaH horas";
        }

        if ($deltaH == 1) {
            return "há uma hora";
        }

        if ($deltaM > 1) {
            return "há $deltaM minutos";
        }

        if ($deltaM == 1) {
            return "há um minuto";
        }

        if ($deltaS > 30) {
            return "há alguns segundos";
        }

        return "mesmo agora";
    }

}