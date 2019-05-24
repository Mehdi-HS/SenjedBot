<?php
require "texts.php";

class botMethods
{
    protected $token = "123456789:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA"; // Bot Token
    public $username = "TEST_Username";                                 // Bot Username
    protected $admins = [56693692];                                     // Array of admina
    private $channel1 = "@SubCreator";                                  // Force ChJoin Ch1 - use "private $channel1 = Null" to disable this feature !
    protected $checkEvery = 300;                                        // Check user if joined to your channels every "$checkEvery" seconds - Higher level improve your bot performance, speed
    public $db, $tMsg;
    public $user_id;

//---------------------------------------------------------------//
    public function __construct()
    {
        $this->db = new PDO("mysql:host=localhost;dbname=Senjed", "USERNAME", "PASSWORD", array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4", PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $this->tMsg = new botMessage();
    }

//---------------------------------------------------------------//
    public function Upload($method, $datas, $type, $file_path)
    {
        $url = "https://api.telegram.org/bot" . $this->token . "/" . $method . "?";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:multipart/form-data']);
        curl_setopt($ch, CURLOPT_URL, $url . http_build_query($datas));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [$type => new \CURLFile($file_path)]);
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);
        if ($result["ok"])
            return $result["result"];
        return false;
    }

//---------------------------------------------------------------//
    public function sendMsg($chat_id, $message, $parse_mode = "", $reply_id = 0, $no_link_preview = "true", $ex = Null)
    {
        $result = $this->https_request("sendmessage", [
            "chat_id" => $chat_id,
            "text" => $message["text"] ?? "404 TXT Func",
            "parse_mode" => empty($parse_mode) ? "" : $parse_mode,
            "reply_to_message_id" => empty($reply_id) ? 0 : $reply_id,
            "disable_web_page_preview" => empty($no_link_preview) ? "true" : $no_link_preview,
            "reply_markup" => $message["keyboard"] ?? ""
        ], $ex);
        return $result;
    }

    //---------------------------------------------------------------//
    public function https_request($method, $parameters, $ex = Null)
    {
        // $ex delete shode.
        $url = "https://api.telegram.org/bot" . $this->token . "/" . $method;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);
        if ($result["ok"] == true)
            return $result["result"];
        return false;
    }

    //---------------------------------------------------------------//
    public function sendPhoto($chat_id, $file_patch, $message, $reply_id = 0)
    {
        $result = $this->Upload("sendphoto", [
            "chat_id" => $chat_id,
            "caption" => $message["text"] ?? "404 TXT Func",
            "reply_to_message_id" => $reply_id,
            "reply_markup" => $message["keyboard"] ?? "",
        ], "photo", $file_patch);
        return $result;
    }

//---------------------------------------------------------------//
    public function fwdMsg($chat_id, $from_chat_id, $msg_id)
    {
        $result = $this->https_request("forwardMessage", [
            "chat_id" => $chat_id,
            "from_chat_id" => $from_chat_id,
            "message_id" => $msg_id,
        ]);
        return $result;
    }

//---------------------------------------------------------------//
    public function getChat_Member($chat_id, $user_id)
    {
        $result = $this->https_request("getChatMember", [
            "chat_id" => $chat_id,
            "user_id" => $user_id,
        ]);
        return $result;
    }

//---------------------------------------------------------------//
    public function keyboard(array $keyboard)
    {
        return '{"keyboard":' . json_encode($keyboard) . ',"one_time_keyboard":false,"resize_keyboard":true}';
    }

//---------------------------------------------------------------//
    public function inline_keyboard(array $inline_keyboard)
    {
        return '{"inline_keyboard":' . json_encode($inline_keyboard) . '}';
    }

//---------------------------------------------------------------//
    public function crateCharge($operator, $charge_code, $price)
    {
        $cart_patch = $this->user_id . "-" . $operator . "-" . $charge_code . "-" . $price . ".jpg";
        $font = './Carts/font.otf';
        $im = imagecreatefromjpeg("$operator.jpg");
        $white = imagecolorallocate($im, 255, 255, 255);
        $grey = imagecolorallocate($im, 128, 128, 128);
        $black = imagecolorallocate($im, 0, 0, 0);
        imagettftext($im, 59, 0, 220, 490, $grey, $font, $charge_code);
        imagettftext($im, 82, 0, 20, 210, $black, $font, $price);
        imagettftext($im, 80, 0, 24, 210, $white, $font, $price);
        imagettftext($im, 48, 0, 295, 210, $black, $font, "rial");
        imagettftext($im, 50, 0, 298, 210, $white, $font, "rial");
        imagepng($im);
        imagejpeg($im, $cart_patch);
        imagedestroy($im);
        return $cart_patch;
    }

//---------------------------------------------------------------//
    public function isJoinedChannelExp($Msg)
    {
        $var = false; // add redis caching
        $query = $this->db->prepare("SELECT `lastCheck` FROM `Users` WHERE (`User_ID` = :User_ID) AND `lastCheck` >= :now_time - " . $this->checkEvery);
        $query->bindParam(':User_ID', $Msg->from->id);
        $query->bindParam(':now_time', $_SERVER["REQUEST_TIME"]);
        $query->execute();
        if (empty($query->fetch())) {
            if (isset($this->channel1)) {
                $res = $this->getChat_Member($this->channel1, $Msg->from->id);
                if ($status = $res["status"]) {
                    if ($status == "left" or $status == "kicked") {
                        $var = true;
                    }
                }
            }
            $query = $this->db->prepare("UPDATE `Users` SET `lastCheck` = :lastCheck WHERE `User_ID` = :User_ID");
            $query->bindParam(':lastCheck', $_SERVER["REQUEST_TIME"]);
            $query->bindParam(':User_ID', $Msg->from->id);
            $query->execute();
        }
        return $var;
    }

//---------------------------------------------------------------//
    public function resolve_username($username)
    {
        $query = $this->db->prepare("SELECT `User_ID` FROM `Users` WHERE `Username` = :username");
        $query->bindParam(':username', $username);
        $query->execute();
        return $query->fetch()['User_ID'] ?? false;
    }

//---------------------------------------------------------------//
    public function updateUsers($username, $name, $user_id)
    {
        $query = $this->db->prepare('INSERT INTO `Users` (`User_ID`,`Username`,`Name`,`joinDate`) VALUES (:user_id, :username, :name, :join_date) ON DUPLICATE KEY UPDATE `User_ID` = :user_id, `Username` = :username, `Name` = :name');
        $query->bindParam(':username', $username);
        $query->bindParam(':name', $name);
        $query->bindParam(':user_id', $user_id);
        $query->bindParam(':join_date', $_SERVER["REQUEST_TIME"]);
        $query->execute();
    }

//---------------------------------------------------------------//
    public function currentStep()
    {
        $query = $this->db->prepare('SELECT `panelStep` FROM `Users` WHERE `User_ID` = :User_ID');
        $query->bindParam(':User_ID', $this->user_id);
        $query->execute();
        return $query->fetch()['panelStep'];
    }

//---------------------------------------------------------------//
    public function getPoints($User_ID = Null)
    {
        if (empty($User_ID))
            $User_ID = $this->user_id;
        $query = $this->db->prepare('SELECT `Points`,`activePoints` FROM `Users` WHERE `User_ID` = :User_ID');
        $query->bindParam(':User_ID', $User_ID);
        $query->execute();
        $result = $query->fetch();
        return [$result[0], $result[1]];
    }

//---------------------------------------------------------------//
    public function getNumber($User_ID = Null)
    {
        if (empty($User_ID))
            $User_ID = $this->user_id;
        $query = $this->db->prepare('SELECT `Number` FROM `Users` WHERE `User_ID` = :User_ID');
        $query->bindParam(':User_ID', $User_ID);
        $query->execute();
        return $query->fetch()["Number"];
    }

//---------------------------------------------------------------//
    public function setNumber($Number)
    {
        $query = $this->db->prepare('UPDATE `Users` SET `Number` = :Number WHERE `User_ID` = :User_ID');
        $query->bindParam(':Number', $Number);
        $query->bindParam(':User_ID', $this->user_id);
        $query->execute();
    }

//---------------------------------------------------------------//
    public function setStep($panelStep)
    {
        $query = $this->db->prepare('UPDATE `Users` SET `panelStep` = :panelStep WHERE `User_ID` = :User_ID');
        $query->bindParam(':panelStep', $panelStep);
        $query->bindParam(':User_ID', $this->user_id);
        $query->execute();
    }

//---------------------------------------------------------------//
    public function getRefer($User_ID = Null)
    {
        if (empty($User_ID))
            $User_ID = $this->user_id;
        $query = $this->db->prepare('SELECT `refBy` FROM `Users` WHERE `User_ID` = :User_ID');
        $query->bindParam(':User_ID', $User_ID);
        $query->execute();
        return $query->fetch()["refBy"];
    }

//---------------------------------------------------------------//
    public function setRefer($refBy)
    {
        $query = $this->db->prepare('UPDATE `Users` SET `refBy` = :refBy WHERE `User_ID` = :User_ID');
        $query->bindParam(':refBy', $refBy);
        $query->bindParam(':User_ID', $this->user_id);
        $query->execute();
        $query = $this->db->prepare('UPDATE `Users` SET `Points` = Points + 3 WHERE `User_ID` = :User_ID');
        $query->bindParam(':User_ID', $refBy);
        $query->execute();
    }

//---------------------------------------------------------------//
    public function activeReferPoints($refBy)
    {
        $query = $this->db->prepare('UPDATE `Users` SET `Points` = Points - 3, `activePoints` = activePoints + 3 WHERE `User_ID` = :User_ID');
        $query->bindParam(':User_ID', $refBy);
        $query->execute();
    }

//---------------------------------------------------------------//
    public function delStep()
    {
        $query = $this->db->prepare("UPDATE `Users` SET `panelStep` = '0' WHERE `User_ID` = :User_ID");
        $query->bindParam(':User_ID', $this->user_id);
        $query->execute();
    }

//---------------------------------------------------------------//
    public function giveGift($count, $User_ID = Null)
    {
        if (empty($User_ID))
            $User_ID = $this->user_id;
        $query = $this->db->prepare("UPDATE `Users` SET `chGift` = '1', `activePoints` = activePoints " . $count . "  WHERE User_ID= :User_ID");
        $query->bindParam(':User_ID', $User_ID);
        $query->execute();
    }

//---------------------------------------------------------------//
    public function checkGift()
    {
        $query = $this->db->prepare('SELECT `chGift` FROM `Users` WHERE `User_ID` = :User_ID');
        $query->bindParam(':User_ID', $this->user_id);
        $query->execute();
        return $query->fetch()["chGift"];
    }

//---------------------------------------------------------------//
    public function checkUser()
    {
        $query = $this->db->prepare('SELECT `*` FROM `Users` WHERE `User_ID` = :User_ID');
        $query->bindParam(':User_ID', $this->user_id);
        $query->execute();
        return $query->fetch();
    }

//---------------------------------------------------------------//
    public function is_admin($User_ID = Null)
    {
        if (empty($User_ID))
            $User_ID = $this->user_id;
        return in_array($User_ID, $this->admins);
    }

//---------------------------------------------------------------//
    public function vardump($var)
    {
        return print_r($var, true);
    }
}