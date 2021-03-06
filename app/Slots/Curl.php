<?php

namespace App\Slots;

class Curl
{
    public static $proxy = false;

    public static $cookie = false;

    public static $user_agent = 'Mozilla/5.0 (compatible; MSIE 7.0; Windows NT 6.0; WOW64; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; c .NET CLR 3.0.04506; .NET CLR 3.5.30707; InfoPath.1; el-GR)';

    public static $last_url;

    private static $ch;

    private static $interface = false;

    public static $verbose = false;

    public static $timeout = 10;

    public static $http_status = 0;
    
    public static function Version()
    {
        print_r(curl_version());
    }
    
    public static function SetInterface($ip)
    {
        self::$interface = $ip;
    }
    
    public static function Start()
    {
        self::$cookie = realpath(self::$cookie);
        self::$ch = curl_init();
    }
    
    public static function SetCookieFile($file)
    {
        self::$cookie = realpath($file.'.cookie');
        self::$ch = curl_init();
        //curl_setopt(self::$ch, CURLOPT_PROXY, '127.0.0.1:8888');
    }
    
    public static function NewSession($file)
    {
        $full_path = $file.'.cookie';        
        if (file_exists($full_path)) {
            unlink($full_path);
        }        
        $ft = fopen($full_path, 'w+');
        fclose($ft);        
        self::$cookie = realpath($full_path);
        self::$ch = curl_init();
    }
    
    public static function SaveCookie()
    {
        curl_close(self::$ch);
        self::$ch = curl_init();
    }
    
    public static function UseProxy($proxy_obj)
    {
        self::$proxy = $proxy_obj;
    }
    
    public static function OpenPage($url, $headers = [], $location = true, $header = false)
    {
        if (self::$proxy) {
            curl_setopt(self::$ch, CURLOPT_PROXY, self::$proxy['host']);
            //curl_setopt(self::$ch, CURLOPT_HTTPPROXYTUNNEL, 1);
            //curl_setopt(self::$ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP_1_0);            
            if (! empty(self::$proxy['auth'])) {
                curl_setopt(self::$ch, CURLOPT_PROXYUSERPWD, self::$proxy['auth']);
            }
        }        
        if (self::$interface) {
            curl_setopt(self::$ch, CURLOPT_INTERFACE, self::$interface);
        }        
        curl_setopt(self::$ch, CURLOPT_URL, $url);
        curl_setopt(self::$ch, CURLOPT_VERBOSE, self::$verbose);
        curl_setopt(self::$ch, CURLOPT_ENCODING, '');
        curl_setopt(self::$ch, CURLOPT_POST, 0);
        curl_setopt(self::$ch, CURLOPT_USERAGENT, self::$user_agent);
        curl_setopt(self::$ch, CURLOPT_COOKIEFILE, self::$cookie);
        curl_setopt(self::$ch, CURLOPT_COOKIEJAR, self::$cookie);
        curl_setopt(self::$ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt(self::$ch, CURLOPT_FAILONERROR, 0);
        curl_setopt(self::$ch, CURLOPT_FOLLOWLOCATION, $location);
        curl_setopt(self::$ch, CURLOPT_HEADER, $header);
        //curl_setopt(self::$ch, CURLOPT_REFERER, $referer);
        curl_setopt(self::$ch, CURLOPT_CONNECTTIMEOUT, self::$timeout);
        curl_setopt(self::$ch, CURLOPT_TIMEOUT, self::$timeout);
        curl_setopt(self::$ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt(self::$ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt(self::$ch, CURLOPT_HTTPHEADER, $headers);        
        $rez = curl_exec(self::$ch);        
        self::$http_status = curl_getinfo(self::$ch, CURLINFO_HTTP_CODE);
        self::$last_url = curl_getinfo(self::$ch, CURLINFO_EFFECTIVE_URL);        
        //print_r(curl_getinfo(self::$ch));        
        return $rez;
    }
    
    public static function PostQuery($url, $data, $headers = [], $location = true, $header = false)
    {
        if (self::$proxy) {
            curl_setopt(self::$ch, CURLOPT_PROXY, self::$proxy['host']);
            //curl_setopt(self::$ch, CURLOPT_HTTPPROXYTUNNEL, 1);
            //curl_setopt(self::$ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP_1_0);            
            if (! empty(self::$proxy['auth'])) {
                curl_setopt(self::$ch, CURLOPT_PROXYUSERPWD, self::$proxy['auth']);
            }
        }        
        if (self::$interface) {
            curl_setopt(self::$ch, CURLOPT_INTERFACE, self::$interface);
        }        
        curl_setopt(self::$ch, CURLOPT_VERBOSE, self::$verbose);
        curl_setopt(self::$ch, CURLOPT_URL, $url);
        curl_setopt(self::$ch, CURLOPT_HEADER, $header);
        curl_setopt(self::$ch, CURLOPT_POST, 1);
        curl_setopt(self::$ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt(self::$ch, CURLOPT_FOLLOWLOCATION, $location);
        curl_setopt(self::$ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt(self::$ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt(self::$ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt(self::$ch, CURLOPT_USERAGENT, self::$user_agent);
        curl_setopt(self::$ch, CURLOPT_COOKIEFILE, self::$cookie);
        curl_setopt(self::$ch, CURLOPT_COOKIEJAR, self::$cookie);
        curl_setopt(self::$ch, CURLOPT_HTTPHEADER, $headers);        
        $res = curl_exec(self::$ch);

        return $res;
    }
    
    public function OpenPages($urls)
    {
        $mh = curl_multi_init();
        unset($conn);
        foreach ($urls as $i => $url) {
            $conn[$i] = curl_init(trim($url));
            curl_setopt($conn[$i], CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($conn[$i], CURLOPT_TIMEOUT, 30);
            curl_setopt($conn[$i], CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)');
            curl_setopt($conn[$i], CURLOPT_COOKIEFILE, self::$cookie);
            curl_setopt($conn[$i], CURLOPT_COOKIEJAR, self::$cookie);
            curl_setopt($conn[$i], CURLOPT_HEADER, 0);
            curl_setopt($conn[$i], CURLOPT_FOLLOWLOCATION, 1);
            curl_multi_add_handle($mh, $conn[$i]);
        }
        do {
            $n = curl_multi_exec($mh, $active);
            usleep(100);
        } while ($active);        
        foreach ($urls as $i => $url) {
            $result[] = curl_multi_getcontent($conn[$i]);
            curl_close($conn[$i]);
        }        
        curl_multi_close($mh);        

        return $result;
    }
    
    public static function Close()
    {
        curl_close(self::$ch);
    }
}
