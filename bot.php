<?php
$msg = json_decode(file_get_contents('php://input')) ?? exit;
date_default_timezone_set("Asia/Tehran");
require "methods.php";
$bot = new botMethods();
if (isset($msg->message)) {
    $Msg = $msg->message;
    if ($Msg->chat->type === "private") {
        $bot->user_id = $Msg->chat->id;
        if (preg_match("/^\/start$|^\/start (.*)/", $Msg->text, $matches)) {
            if (empty($matches[1])) {
                $bot->updateUsers(isset($Msg->from->username) ? strtolower($Msg->from->username) : NULL, isset($Msg->from->first_name) ? $Msg->from->first_name : NULL, $Msg->from->id);
                if (empty($bot->getNumber())) {
                    $bot->setStep(16);
                    $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(16), Null, $Msg->message_id);
                } else
                    $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(1), Null, $Msg->message_id, Null, true);
            } else {
                $user_id = base64_decode($matches[1]);
                $step = $bot->currentStep();
                $step = empty($step) ? 1 : $step;
                if (empty(ctype_digit($user_id)))
                    $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(13, $step), Null, $Msg->message_id, Null, true);
                elseif ($user_id == $Msg->from->id)
                    $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(14, $step), Null, $Msg->message_id, Null, true);
                elseif ($bot->checkUser()["User_ID"])
                    $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(15, $step), Null, $Msg->message_id, Null, true);
                else {
                    $bot->updateUsers(isset($Msg->from->username) ? strtolower($Msg->from->username) : NULL, isset($Msg->from->first_name) ? $Msg->from->first_name : NULL, $Msg->from->id);
                    $bot->setRefer($user_id);
                    $bot->setStep(16);
                    $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(16), Null, $Msg->message_id, Null, true);
                }
            }
        } else {
            $bot->updateUsers(isset($Msg->from->username) ? strtolower($Msg->from->username) : NULL, isset($Msg->from->first_name) ? $Msg->from->first_name : NULL, $Msg->from->id);
            if ($step = $bot->currentStep()) {
                switch ($step) {
                    case 3:
                        if ($bot->isJoinedChannelExp($Msg))
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(2, $Msg->from->first_name), Null, $Msg->message_id, Null, true);
                        else
                            if ($Msg->text == "⏳ عضویت در کانال سنجد (5 سنجد)") {
                                if (empty($bot->checkGift())) {
                                    $bot->giveGift("+5");
                                    $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(7, $step), Null, $Msg->message_id, Null, true);
                                } else
                                    $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(6, $step), Null, $Msg->message_id, Null, true);
                            } elseif ($Msg->text == "✅ دعوت کاربران به ربات (3 سنجد)") {
                                $result = $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(11));
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(12, base64_encode($Msg->from->id)), Null, $result["message_id"], Null, true);
                            } elseif ($Msg->text == "برگشت🔙" or $Msg->text == "/start") {
                                $bot->delStep();
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(1), Null, $Msg->message_id, Null, true);
                            } else
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(404, $step), Null, $Msg->message_id, Null, true);
                        break;
                    case 5:
                        if ($bot->isJoinedChannelExp($Msg))
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(2, $Msg->from->first_name), Null, $Msg->message_id, Null, true);
                        else
                            if ($Msg->text == "⏳ تبدیل سنجد به شارژ") {
                                $bot->setStep(8);
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(8), Null, $Msg->message_id, Null, true);
                            } elseif ($Msg->text == "⏳ تبدیل سنجد به کدتخفیف") {
                                $bot->setStep(9);
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(9), Null, $Msg->message_id, Null, true);
                            } elseif ($Msg->text == "⏳ تبدیل سنجد به پول نقد") {
                                $bot->setStep(10);
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(10), Null, $Msg->message_id, Null, true);
                            } elseif ($Msg->text == "برگشت🔙" or $Msg->text == "/start") {
                                $bot->delStep();
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(1), Null, $Msg->message_id, Null, true);
                            } else
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(404, $step), Null, $Msg->message_id, Null, true);
                        break;
                    case 8:
                        if ($bot->isJoinedChannelExp($Msg))
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(2, $Msg->from->first_name), Null, $Msg->message_id, Null, true);
                        else
                            if ($Msg->text == "📲ایرانسل") {
                            } elseif ($Msg->text == "📲همراه اول") {
                            } elseif ($Msg->text == "📲رایتل") {
                            } elseif ($Msg->text == "برگشت🔙" or $Msg->text == "/start") {
                                $bot->setStep(5);
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(5), Null, $Msg->message_id, Null, true);
                            } elseif ($Msg->text == "منو اصلی⏪") {
                                $bot->delStep();
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(1), Null, $Msg->message_id, Null, true);
                            } else
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(404, $step), Null, $Msg->message_id, Null, true);
                        break;
                    case 9:
                        if ($bot->isJoinedChannelExp($Msg))
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(2, $Msg->from->first_name), Null, $Msg->message_id, Null, true);
                        else
                            if ($Msg->text == "💼 کارت تخفیف دیجیکالا") {
                            } elseif ($Msg->text == "🍕 کارت تخفیف اسنپ فود") {
                            } elseif ($Msg->text == "🚕 کارت تخفیف اسنپ") {
                            } elseif ($Msg->text == "برگشت🔙" or $Msg->text == "/start") {
                                $bot->setStep(5);
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(5), Null, $Msg->message_id, Null, true);
                            } elseif ($Msg->text == "منو اصلی⏪") {
                                $bot->delStep();
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(1), Null, $Msg->message_id, Null, true);
                            } else
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(404, $step), Null, $Msg->message_id, Null, true);
                        break;
                    case 10:
                        if ($bot->isJoinedChannelExp($Msg))
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(2, $Msg->from->first_name), Null, $Msg->message_id, Null, true);
                        else
                            if ($Msg->text == "100 هزار تومان (1400 سنجد)") {
                            } elseif ($Msg->text == "200 هزار تومان (2700 سنجد)") {
                            } elseif ($Msg->text == "500 هزار تومان (6700 سنجد)") {
                            } elseif ($Msg->text == "برگشت🔙" or $Msg->text == "/start") {
                                $bot->setStep(5);
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(5), Null, $Msg->message_id, Null, true);
                            } elseif ($Msg->text == "منو اصلی⏪") {
                                $bot->delStep();
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(1), Null, $Msg->message_id, Null, true);
                            } else
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(404, $step), Null, $Msg->message_id, Null, true);
                        break;
                    case 16:
                        if (isset($Msg->contact)) {
                            if (isset($Msg->contact->user_id) and $Msg->contact->user_id == $Msg->from->id) {
                                if (substr($Msg->contact->phone_number, 0, 2) == 98 or substr($Msg->contact->phone_number, 0, 3) == "+98") {
                                    $bot->setNumber($Msg->contact->phone_number);
                                    if ($refBy = $bot->getRefer())
                                        $bot->activeReferPoints($refBy);
                                    $bot->delStep();
                                    $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(20), Null, $Msg->message_id, Null, true);
                                } else
                                    $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(17), Null, $Msg->message_id, Null, true);
                            } else
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(18), Null, $Msg->message_id, Null, true);
                        } else
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(19), Null, $Msg->message_id, Null, true);
                        break;
                    case 21:
                        if ($Msg->text == "100 هزار تومان (1400 سنجد)") {
                        } elseif ($Msg->text == "200 هزار تومان (2700 سنجد)") {
                        } elseif ($Msg->text == "500 هزار تومان (6700 سنجد)") {
                        } elseif ($Msg->text == "برگشت🔙" or $Msg->text == "/start") {
                            $bot->delStep();
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(1), Null, $Msg->message_id, Null, true);
                        } else
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(404, $step), Null, $Msg->message_id, Null, true);
                        break;
                    default:
                        $bot->delStep();
                        $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(422, $step), Null, $Msg->message_id, Null, true);
                }
            } ////////////////////////////////////////////////////////////////////////////////////////////////////////
            elseif ($Msg->text === "/panel" and $bot->is_admin()) {
                $bot->setStep(21);
                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(21), Null, $Msg->message_id, Null, true);
            } ////////////////////////////////////////////////////////////////////////////////////////////////////////
            elseif ($bot->isJoinedChannelExp($Msg))
                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(2, $Msg->from->first_name), Null, $Msg->message_id, Null, true);
            ////////////////////////////////////////////////////////////////////////////////////////////////////////
            else if ($Msg->text === "🌱سنجد های من") {
                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(4), Null, $Msg->message_id, Null, true);
            } ////////////////////////////////////////////////////////////////////////////////////////////////////////
            else if ($Msg->text === "🛍تبدیل سنجد به...") {
                $bot->setStep(5);
                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(5), Null, $Msg->message_id, Null, true);
            } ////////////////////////////////////////////////////////////////////////////////////////////////////////
            else if ($Msg->text === "❓چجوری سنجد جمع کنم❓") {
                $bot->setStep(3);
                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(3), Null, $Msg->message_id, Null, true);
            } ////////////////////////////////////////////////////////////////////////////////////////////////////////
            else {
                if (empty($bot->getNumber())) {
                    $bot->setStep(16);
                    $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(16), Null, $Msg->message_id);
                } else
                    $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(404, 1), Null, $Msg->message_id, Null, true);
            }
        }
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
    }
} else if (isset($msg->callback_query)) {
    $Msg = $msg->callback_query;
}
exit;