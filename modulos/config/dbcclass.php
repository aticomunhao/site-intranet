<?php
$url = $_SERVER["PHP_SELF"];
$urlIni = "/site-intranet/";
if(strtolower($url) == $urlIni."modulos/config/dbcclass.php"){
    header("Location: $urlIni");
}else{

    function conecPost(){
        $con_string = "host = 192.168.1.143 port=5432 dbname=cesb user=postgres password=scga2298";
        if(function_exists("pg_pconnect")){ // para o caso de a extension=pgsql não estar habilitada no phpini
            if(@pg_connect($con_string)){
                $Con = @pg_connect($con_string) or die("Não foi possível conectar-se ao banco de dados. String");
            }else{
                $Con = "sConec";
            }
        }else{
            $Con = "sFunc";
        }
        return $Con;
    }

    function conecPes(){
        $con_stringpes = "host= 192.168.1.143 port=5432 dbname=pessoal user=postgres password=scga2298";
        if(function_exists("pg_pconnect")){ // para o caso de a extension=pgsql não estar habilitada no phpini
            if(@pg_connect($con_stringpes)){
                $Conpes = @pg_connect($con_stringpes) or die("Não foi possível conectar-se ao banco de dados.");
            }else{
                $Conpes = "sConec";
            }
        }else{
            $Conpes = "sFunc";
        }
        return $Conpes;
    }
}