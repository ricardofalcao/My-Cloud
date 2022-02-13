<?php

namespace Core;

class Utils
{


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