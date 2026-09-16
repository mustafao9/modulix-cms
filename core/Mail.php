<?php
class Mail {
    public static function gonder($alici, $konu, $mesaj) {
        $db = Database::baglan();
        $stmt = $db->query("SELECT anahtar, deger FROM ayarlar WHERE anahtar LIKE 'smtp_%'");
        $ayarlar = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        $host = $ayarlar['smtp_host'] ?? '';
        $port = $ayarlar['smtp_port'] ?? '587';
        $user = $ayarlar['smtp_user'] ?? '';
        $pass = $ayarlar['smtp_pass'] ?? '';
        $secure = $ayarlar['smtp_secure'] ?? 'tls';

        if (empty($host) || empty($user)) {
            // SMTP Yapılandırılmamışsa PHP Native mail fallback
            $headers = "MIME-Version: 1.0\r\nContent-Type: text/html; charset=UTF-8\r\nFrom: Modulix-CMS <noreply@site.com>\r\n";
            return @mail($alici, $konu, $mesaj, $headers);
        }

        try {
            $prefix = ($secure === 'ssl') ? 'ssl://' : '';
            $socket = @fsockopen($prefix . $host, $port, $errno, $errstr, 10);
            if (!$socket) return false;

            self::readResponse($socket);
            fwrite($socket, "EHLO " . gethostname() . "\r\n");
            self::readResponse($socket);

            if ($secure === 'tls') {
                fwrite($socket, "STARTTLS\r\n");
                self::readResponse($socket);
                stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                fwrite($socket, "EHLO " . gethostname() . "\r\n");
                self::readResponse($socket);
            }

            fwrite($socket, "AUTH LOGIN\r\n");
            self::readResponse($socket);
            fwrite($socket, base64_encode($user) . "\r\n");
            self::readResponse($socket);
            fwrite($socket, base64_encode($pass) . "\r\n");
            self::readResponse($socket);

            fwrite($socket, "MAIL FROM: <{$user}>\r\n");
            self::readResponse($socket);
            fwrite($socket, "RCPT TO: <{$alici}>\r\n");
            self::readResponse($socket);

            fwrite($socket, "DATA\r\n");
            self::readResponse($socket);

            $content = "Subject: =?UTF-8?B?" . base64_encode($konu) . "?=\r\n";
            $content .= "From: Modulix-CMS <{$user}>\r\n";
            $content .= "To: {$alici}\r\n";
            $content .= "MIME-Version: 1.0\r\n";
            $content .= "Content-Type: text/html; charset=UTF-8\r\n\r\n";
            $content .= $mesaj . "\r\n.\r\n";

            fwrite($socket, $content);
            self::readResponse($socket);

            fwrite($socket, "QUIT\r\n");
            fclose($socket);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    private static function readResponse($socket) {
        $res = '';
        while ($str = fgets($socket, 512)) {
            $res .= $str;
            if (substr($str, 3, 1) == " ") break;
        }
        return $res;
    }
}
