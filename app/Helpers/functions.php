<?php

    //RETORNA A DATA DA ÚLTIMA SEGUNDA-FEIRA
    function monday($date)
    {
        $weekDay = date('N', strtotime($date));

        if($weekDay == 1)
        {
            $monday = $date;
        }
        else
        {
            $monday = date('Y-m-d', strtotime('last monday', strtotime($date)));
        }

        return $monday;
    }

    //RETORNA O PRIMEIRO DIA DO MÊS
    function firstDayOfMonth($date)
    {
        $firstDayOfMonth = date('Y-m-d', strtotime('first day of this month', strtotime($date)));

        return $firstDayOfMonth;
    }

    //FORMATA EXIBIÇÃO DA DATA EM PORTUGUÊS, DE ACORDO COM O PADRÃO FORNECIDO
    function formatDate($date, $pattern)
    {
        $fmt = new IntlDateFormatter('pt_BR',
        IntlDateFormatter::FULL,
        IntlDateFormatter::FULL,
        'America/Sao_Paulo',
        IntlDateFormatter::GREGORIAN);

        $fmt->setPattern($pattern);
        $formattedDate = $fmt->format(strtotime($date));

        return $formattedDate;
    }

    //FORMATA DATA PARA PADRÃO AMERICANO
    function parseDate($date)
    {
        $date = str_replace('/', '-', $date);
        $date = strtotime($date);

        if(!$date)
        {
            $date = time();
        }

        return date('Y-m-d', $date);
    }

    function parseDateTime($datetime)
    {
        $datetime = str_replace('/', '-', $datetime);
        $datetime = strtotime($datetime);

        if(!$datetime)
        {
            $datetime = time();
        }

        return date('Y-m-d H:i:s', $datetime);
    }

    //VERFIFICA SE UMA DATA É VÁLIDA
    function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    //FORMATA MOEDA PARA PADRÃO AMERICANO
    function parseMoney($amount)
    {
        $search = array('.', ',');
        $replace = array('', '.');

        return str_replace($search, $replace, $amount);
    }

    //CONVERTE ARRAY MULTIDIMENCIONAL PARA OBJETO
    function toObject($arr)
    {
        return (is_array($arr) ? (object) array_map( __FUNCTION__, $arr) : $arr);
    }

    //LIMPAR STRING, RETORNANDO SOMENTE NUMEROS

    function onlyNumbers(string $string): string
    {
        $string = preg_replace('/[^0-9]/', '', $string);

        return $string;
    }

    //FORMATAR CPF
    function formatCPF($cpf)
    {
        $formattedCPF = substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) .
                '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);

        return $formattedCPF;
    }

    //FORMATAR CNPJ
    function formatCNPJ($cnpj)
    {
        $formattedCNPJ = substr($cnpj, 0, 2) . '.' . substr($cnpj, 2, 3) .
                '.' . substr($cnpj, 5, 3) . '/' . substr($cnpj, 8, 4) .
                '-' .  substr($cnpj, 12, 2);

        return $formattedCNPJ;
    }

    //FORMATAR IE
    function formatIE($ie)
    {
        $formattedIE = substr($ie, 0, 3) . '.' . substr($ie, 3, 3) .
                '.' . substr($ie, 6, 3) . '.' . substr($ie, 9, 3);

        return $formattedIE;
    }

    //LIMPAR CEP

    function cleanZipCode(string $zipCode): string
    {
        $zipCode = preg_replace('/[^0-9]/', '', $zipCode);
        if (strlen($zipCode) === 8) {
            return $zipCode;
        }
        return "00000000";
    }

    //CURL POST
    function curlPost($url, $postfields = [], $decode = false, $headers = [])
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 400);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $output = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ($ch);

        return json_decode($output, $decode);
    }

    //CURL GET
    function curlGet($url, $decode = false, $headers = [])
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 400);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $output = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ($ch);

        return json_decode($output, $decode);
    }

    //CURL PUT
    function curlPut($url, $postfields = [], $decode = false, $headers = [])
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 400);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $output = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ($ch);

        return json_decode($output, $decode);
    }

    //CURL DELETE
    function curlDelete($url, $decode = false, $headers = [])
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 400);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $output = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ($ch);

        return json_decode($output, $decode);
    }

    //CONVERTE IMAGEM PARA WEBP
    function webpImage($source, $quality = 100, $removeOld = false)
    {
        $dir = pathinfo($source, PATHINFO_DIRNAME);
        $name = pathinfo($source, PATHINFO_FILENAME);
        $destination = $dir . DIRECTORY_SEPARATOR . $name . '.webp';
        $info = getimagesize($source);
        $isAlpha = false;
        if($info['mime'] == 'image/jpeg')
        {
            $image = imagecreatefromjpeg($source);
        }
        elseif($isAlpha = $info['mime'] == 'image/gif')
        {
            $image = imagecreatefromgif($source);
        }
        elseif($isAlpha = $info['mime'] == 'image/png')
        {
            $image = imagecreatefrompng($source);
        }
        else
        {
            return $source;
        }
        if ($isAlpha)
        {
            imagepalettetotruecolor($image);
            imagealphablending($image, true);
            imagesavealpha($image, true);
        }
        imagewebp($image, $destination, $quality);

        if($removeOld)
        {
            unlink($source);
        }
        return $destination;
    }

    //CRIA ARQUIVO DE TEXTO NO CAMINHO INDICADO
    function writeFile($filePath, $content)
    {
        $file = fopen($filePath, 'a');
        fwrite($file, $content);
        fclose($file);

        return true;
    }

    function parseCents($amount)
    {
        $amount = $amount * 100;
        $amount = number_format($amount, 0, '', '');

        return $amount;
    }
