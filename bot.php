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
                                $bot->setStep(28, 1);
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(28), Null, $Msg->message_id, Null, true);
                            } elseif ($Msg->text == "📲همراه اول") {
                                $bot->setStep(28, 2);
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(28), Null, $Msg->message_id, Null, true);
                            } elseif ($Msg->text == "📲رایتل") {
                                $bot->setStep(28, 3);
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(28), Null, $Msg->message_id, Null, true);
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
                                $bot->setStep(30);
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(30), Null, $Msg->message_id, Null, true);
                            } elseif ($Msg->text == "🍕 کارت تخفیف اسنپ فود") {
                                $bot->setStep(31, 1);
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(31, true), Null, $Msg->message_id, Null, true);
                            } elseif ($Msg->text == "🚕 کارت تخفیف اسنپ") {
                                $bot->setStep(31, 2);
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(31), Null, $Msg->message_id, Null, true);
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
                        else {
                            $activePoints = $bot->getPoints()[1];
                            if ($Msg->text == "100 هزار تومان (1400 سنجد)") {
                                if ($activePoints >= 1400) {
                                    $bot->setStep(36, 1);
                                    $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(36), Null, $Msg->message_id, Null, true);
                                } else
                                    $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(-5, [$activePoints, 1400]), Null, $Msg->message_id, Null, true);
                            } elseif ($Msg->text == "200 هزار تومان (2700 سنجد)") {
                                if ($activePoints >= 2700) {
                                    $bot->setStep(36, 2);
                                    $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(36), Null, $Msg->message_id, Null, true);
                                } else
                                    $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(-5, [$activePoints, 2700]), Null, $Msg->message_id, Null, true);
                            } elseif ($Msg->text == "500 هزار تومان (6700 سنجد)") {
                                if ($activePoints >= 6700) {
                                    $bot->setStep(36, 3);
                                    $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(36), Null, $Msg->message_id, Null, true);
                                } else
                                    $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(-5, [$activePoints, 6700]), Null, $Msg->message_id, Null, true);
                            } elseif ($Msg->text == "برگشت🔙" or $Msg->text == "/start") {
                                $bot->setStep(5);
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(5), Null, $Msg->message_id, Null, true);
                            } elseif ($Msg->text == "منو اصلی⏪") {
                                $bot->delStep();
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(1), Null, $Msg->message_id, Null, true);
                            } else
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(404, $step), Null, $Msg->message_id, Null, true);
                        }
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
                        if ($Msg->text == "افزودن اعتبار") {
                            $bot->setStep(22);
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(22), Null, $Msg->message_id, Null, true);
                        } elseif ($Msg->text == "آمار ربات")
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(35, $bot->getStats()), Null, $Msg->message_id, Null, true);
                        //$bot->sendMsg($Msg->chat->id, ["text" => $bot->vardump($bot->getStats())], Null, $Msg->message_id, Null, true);
                        elseif ($Msg->text == "برگشت🔙" or $Msg->text == "/start") {
                            $bot->delStep();
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(1), Null, $Msg->message_id, Null, true);
                        } else
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(404, $step), Null, $Msg->message_id, Null, true);
                        break;
                    case 22:
                        if ($Msg->text == "افزودن اعتبار شارژ") {
                            $bot->setStep(23);
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(23), Null, $Msg->message_id, Null, true);
                        } elseif ($Msg->text == "افزودن کد تخفیف") {
                            $bot->setStep(26);
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(26), Null, $Msg->message_id, Null, true);
                        } elseif ($Msg->text == "برگشت🔙" or $Msg->text == "/start") {
                            $bot->setStep(21);
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(21), Null, $Msg->message_id, Null, true);
                        } else
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(404, $step), Null, $Msg->message_id, Null, true);
                        break;
                    case 23:
                        if ($Msg->text == "برگشت🔙" or $Msg->text == "/start") {
                            $bot->setStep(22);
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(22), Null, $Msg->message_id, Null, true);
                        } elseif (mb_strlen($Msg->text) > 255)
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(24, $step), Null, $Msg->message_id, Null, true);
                        else {
                            $bot->setStep(25, $Msg->text);
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(25), Null, $Msg->message_id, Null, true);
                        }
                        break;
                    case 25:
                        $code = $bot->getExtra();
                        if ($Msg->text == "ایرانسل - شارژ 2,000 تومانی")
                            $bot->addCredit($code, 10);
                        elseif ($Msg->text == "ایرانسل - شارژ 5,000 تومانی")
                            $bot->addCredit($code, 11);
                        elseif ($Msg->text == "ایرانسل - شارژ 20,000 تومانی")
                            $bot->addCredit($code, 12);
                        elseif ($Msg->text == "همراه اول - شارژ 2,000 تومانی")
                            $bot->addCredit($code, 20);
                        elseif ($Msg->text == "همراه اول - شارژ 5,000 تومانی")
                            $bot->addCredit($code, 21);
                        elseif ($Msg->text == "همراه اول - شارژ 20,000 تومانی")
                            $bot->addCredit($code, 22);
                        elseif ($Msg->text == "رایتل - شارژ 2,000 تومانی")
                            $bot->addCredit($code, 30);
                        elseif ($Msg->text == "رایتل - شارژ 5,000 تومانی")
                            $bot->addCredit($code, 31);
                        elseif ($Msg->text == "رایتل - شارژ 20,000 تومانی")
                            $bot->addCredit($code, 32);
                        elseif ($Msg->text == "برگشت🔙" or $Msg->text == "/start") {
                            $bot->setStep(23);
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(23), Null, $Msg->message_id, Null, true);
                        } else
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(404, $step), Null, $Msg->message_id, Null, true);
                        break;
                    case 26:
                        if ($Msg->text == "برگشت🔙" or $Msg->text == "/start") {
                            $bot->setStep(22);
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(22), Null, $Msg->message_id, Null, true);
                        } elseif (mb_strlen($Msg->text) > 255)
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(24, $step), Null, $Msg->message_id, Null, true);
                        else {
                            $bot->setStep(27, $Msg->text);
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(27), Null, $Msg->message_id, Null, true);
                        }
                        break;
                    case 27:
                        $code = $bot->getExtra();
                        if ($Msg->text == "اسنپ - تخفیف 5,000 تومانی")
                            $bot->addCredit($code, 40);
                        elseif ($Msg->text == "اسنپ - تخفیف 10,000 تومانی")
                            $bot->addCredit($code, 41);
                        elseif ($Msg->text == "اسنپ - تخفیف 20,000 تومانی")
                            $bot->addCredit($code, 42);
                        elseif ($Msg->text == "اسنپ فود - تخفیف 5,000 تومانی")
                            $bot->addCredit($code, 60);
                        elseif ($Msg->text == "اسنپ فود - تخفیف 10,000 تومانی")
                            $bot->addCredit($code, 61);
                        elseif ($Msg->text == "اسنپ فود - تخفیف 20,000 تومانی")
                            $bot->addCredit($code, 62);
                        elseif ($Msg->text == "دیجیکالا - تخفیف 50,000 تومانی")
                            $bot->addCredit($code, 50);
                        elseif ($Msg->text == "دیجیکالا - تخفیف 100,000 تومانی")
                            $bot->addCredit($code, 51);
                        elseif ($Msg->text == "برگشت🔙" or $Msg->text == "/start") {
                            $bot->setStep(26);
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(26), Null, $Msg->message_id, Null, true);
                        } else
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(404, $step), Null, $Msg->message_id, Null, true);
                        break;
                    case 28:
                        $activePoints = $bot->getPoints()[1];
                        if ($Msg->text == "شارژ 2,000 تومانی (30 سنجد)") {
                            if ($activePoints >= 30) {
                                $bot->setStep(8);
                                $operator = $bot->getExtra();
                                switch ($operator) {
                                    case 1:
                                        if ($code = $bot->getCredit(10)) {
                                            $bot->giveGift("-30");
                                            $bot->expireCredit($code);
                                            $chargeCart = $bot->createCharge("irancell", $code, "20000");
                                            $bot->sendPhoto($Msg->chat->id, $chargeCart, $bot->tMsg->get(29, $code), $Msg->message_id);
                                            unlink($chargeCart);
                                        } else
                                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(-1), Null, $Msg->message_id, Null, true);
                                        break;
                                    case 2:
                                        if ($code = $bot->getCredit(20)) {
                                            $bot->giveGift("-30");
                                            $bot->expireCredit($code);
                                            $chargeCart = $bot->createCharge("mci", $code, "20000");
                                            $bot->sendPhoto($Msg->chat->id, $chargeCart, $bot->tMsg->get(29, $code), $Msg->message_id);
                                            unlink($chargeCart);
                                        } else
                                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(-1), Null, $Msg->message_id, Null, true);
                                        break;
                                    case 3:
                                        if ($code = $bot->getCredit(30)) {
                                            $bot->giveGift("-30");
                                            $bot->expireCredit($code);
                                            $chargeCart = $bot->createCharge("rightel", $code, "20000");
                                            $bot->sendPhoto($Msg->chat->id, $chargeCart, $bot->tMsg->get(29, $code), $Msg->message_id);
                                            unlink($chargeCart);
                                        } else
                                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(-1), Null, $Msg->message_id, Null, true);
                                        break;
                                }
                            } else
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(-2, [$activePoints, 30]), Null, $Msg->message_id, Null, true);
                        } elseif ($Msg->text == "شارژ 5,000 تومانی (75 سنجد)") {
                            if ($activePoints >= 75) {
                                $bot->setStep(8);
                                $operator = $bot->getExtra();
                                switch ($operator) {
                                    case 1:
                                        if ($code = $bot->getCredit(11)) {
                                            $bot->giveGift("-75");
                                            $bot->expireCredit($code);
                                            $chargeCart = $bot->createCharge("irancell", $code, "50000");
                                            $bot->sendPhoto($Msg->chat->id, $chargeCart, $bot->tMsg->get(29, $code), $Msg->message_id);
                                            unlink($chargeCart);
                                        } else
                                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(-1), Null, $Msg->message_id, Null, true);
                                        break;
                                    case 2:
                                        if ($code = $bot->getCredit(21)) {
                                            $bot->giveGift("-75");
                                            $bot->expireCredit($code);
                                            $chargeCart = $bot->createCharge("mci", $code, "50000");
                                            $bot->sendPhoto($Msg->chat->id, $chargeCart, $bot->tMsg->get(29, $code), $Msg->message_id);
                                            unlink($chargeCart);
                                        } else
                                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(-1), Null, $Msg->message_id, Null, true);
                                        break;
                                    case 3:
                                        if ($code = $bot->getCredit(31)) {
                                            $bot->giveGift("-75");
                                            $bot->expireCredit($code);
                                            $chargeCart = $bot->createCharge("rightel", $code, "50000");
                                            $bot->sendPhoto($Msg->chat->id, $chargeCart, $bot->tMsg->get(29, $code), $Msg->message_id);
                                            unlink($chargeCart);
                                        } else
                                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(-1), Null, $Msg->message_id, Null, true);
                                        break;
                                }
                            } else
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(-2, [$activePoints, 75]), Null, $Msg->message_id, Null, true);
                        } elseif ($Msg->text == "شارژ 20,000 تومانی (290 سنجد)") {
                            if ($activePoints >= 290) {
                                $bot->setStep(8);
                                $operator = $bot->getExtra();
                                switch ($operator) {
                                    case 1:
                                        if ($code = $bot->getCredit(12)) {
                                            $bot->giveGift("-290");
                                            $bot->expireCredit($code);
                                            $chargeCart = $bot->createCharge("irancell", $code, "200000");
                                            $bot->sendPhoto($Msg->chat->id, $chargeCart, $bot->tMsg->get(29, $code), $Msg->message_id);
                                            unlink($chargeCart);
                                        } else
                                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(-1), Null, $Msg->message_id, Null, true);
                                        break;
                                    case 2:
                                        if ($code = $bot->getCredit(22)) {
                                            $bot->giveGift("-290");
                                            $bot->expireCredit($code);
                                            $chargeCart = $bot->createCharge("mci", $code, "200000");
                                            $bot->sendPhoto($Msg->chat->id, $chargeCart, $bot->tMsg->get(29, $code), $Msg->message_id);
                                            unlink($chargeCart);
                                        } else
                                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(-1), Null, $Msg->message_id, Null, true);
                                        break;
                                    case 3:
                                        if ($code = $bot->getCredit(32)) {
                                            $bot->giveGift("-290");
                                            $bot->expireCredit($code);
                                            $chargeCart = $bot->createCharge("rightel", $code, "200000");
                                            $bot->sendPhoto($Msg->chat->id, $chargeCart, $bot->tMsg->get(29, $code), $Msg->message_id);
                                            unlink($chargeCart);
                                        } else
                                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(-1), Null, $Msg->message_id, Null, true);
                                        break;
                                }
                            } else
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(-2, [$activePoints, 290]), Null, $Msg->message_id, Null, true);
                        } elseif ($Msg->text == "برگشت🔙") {
                            $bot->setStep(8);
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(8), Null, $Msg->message_id, Null, true);
                        } else {
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(404, $step), Null, $Msg->message_id, Null, true);
                        }
                        break;
                    case 30:
                        $activePoints = $bot->getPoints()[1];
                        if ($Msg->text == "کارت تخفیف 50,000 تومانی دیجیکالا (680 سنجد)") {
                            if ($activePoints >= 680) {
                                $bot->setStep(9);
                                if ($code = $bot->getCredit(50)) {
                                    $bot->giveGift("-680");
                                    $bot->expireCredit($code);
                                    $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(32, $code), Null, $Msg->message_id, Null, true);
                                } else
                                    $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(-4), Null, $Msg->message_id, Null, true);
                            } else
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(-3, [$activePoints, 680]), Null, $Msg->message_id, Null, true);
                        } elseif ($Msg->text == "کارت تخفیف 100,000 تومانی دیجیکالا (1220 سنجد)") {
                            if ($activePoints >= 1220) {
                                $bot->setStep(9);
                                if ($code = $bot->getCredit(51)) {
                                    $bot->giveGift("-1220");
                                    $bot->expireCredit($code);
                                    $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(33, $code), Null, $Msg->message_id, Null, true);
                                } else
                                    $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(-4), Null, $Msg->message_id, Null, true);
                            } else
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(-3, [$activePoints, 1220]), Null, $Msg->message_id, Null, true);
                        } elseif ($Msg->text == "برگشت🔙") {
                            $bot->setStep(9);
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(9), Null, $Msg->message_id, Null, true);
                        } else {
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(404, $step), Null, $Msg->message_id, Null, true);
                        }
                        break;
                    case 31:
                        $activePoints = $bot->getPoints()[1];
                        if ($Msg->text == "تخفیف 5,000 تومانی اسنپ فود (70 سنجد)" or $Msg->text == "تخفیف 5,000 تومانی اسنپ (70 سنجد)") {
                            if ($activePoints >= 70) {
                                $bot->setStep(9);
                                $mode = $bot->getExtra();
                                switch ($mode) {
                                    case 1:
                                        if ($code = $bot->getCredit(60)) {
                                            $bot->giveGift("-70");
                                            $bot->expireCredit($code);
                                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(34, [5000, $code]), Null, $Msg->message_id, Null, true);
                                        } else
                                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(-4), Null, $Msg->message_id, Null, true);
                                        break;
                                    case 2:
                                        if ($code = $bot->getCredit(40)) {
                                            $bot->giveGift("-70");
                                            $bot->expireCredit($code);
                                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(34, [5000, $code]), Null, $Msg->message_id, Null, true);
                                        } else
                                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(-4), Null, $Msg->message_id, Null, true);
                                        break;
                                }
                            } else
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(-3, [$activePoints, 70]), Null, $Msg->message_id, Null, true);
                        } elseif ($Msg->text == "تخفیف 10,000 تومانی اسنپ فود (140 سنجد)" or $Msg->text == "تخفیف 10,000 تومانی اسنپ (140 سنجد)") {
                            if ($activePoints >= 140) {
                                $bot->setStep(9);
                                $mode = $bot->getExtra();
                                switch ($mode) {
                                    case 1:
                                        if ($code = $bot->getCredit(61)) {
                                            $bot->giveGift("-140");
                                            $bot->expireCredit($code);
                                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(34, [10000, $code]), Null, $Msg->message_id, Null, true);
                                        } else
                                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(-4), Null, $Msg->message_id, Null, true);
                                        break;
                                    case 2:
                                        if ($code = $bot->getCredit(41)) {
                                            $bot->giveGift("-140");
                                            $bot->expireCredit($code);
                                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(34, [10000, $code]), Null, $Msg->message_id, Null, true);
                                        } else
                                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(-4), Null, $Msg->message_id, Null, true);
                                        break;
                                }
                            } else
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(-3, [$activePoints, 140]), Null, $Msg->message_id, Null, true);
                        } elseif ($Msg->text == "تخفیف 20,000 تومانی اسنپ فود (270 سنجد)" or $Msg->text == "تخفیف 20,000 تومانی اسنپ (270 سنجد)") {
                            if ($activePoints >= 270) {
                                $bot->setStep(9);
                                $mode = $bot->getExtra();
                                switch ($mode) {
                                    case 1:
                                        if ($code = $bot->getCredit(62)) {
                                            $bot->giveGift("-270");
                                            $bot->expireCredit($code);
                                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(34, [20000, $code]), Null, $Msg->message_id, Null, true);
                                        } else
                                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(-4), Null, $Msg->message_id, Null, true);
                                        break;
                                    case 2:
                                        if ($code = $bot->getCredit(42)) {
                                            $bot->giveGift("-270");
                                            $bot->expireCredit($code);
                                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(34, [20000, $code]), Null, $Msg->message_id, Null, true);
                                        } else
                                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(-4), Null, $Msg->message_id, Null, true);
                                        break;
                                }
                            } else
                                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(-3, [$activePoints, 270]), Null, $Msg->message_id, Null, true);
                        } elseif ($Msg->text == "برگشت🔙") {
                            $bot->setStep(9);
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(9), Null, $Msg->message_id, Null, true);
                        } else {
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(404, $step), Null, $Msg->message_id, Null, true);
                        }
                        break;
                    case 36:
                        if ($Msg->text == "برگشت🔙") {
                            $bot->setStep(10);
                            $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(10), Null, $Msg->message_id, Null, true);
                        } else {
                            $activePoints = $bot->getPoints()[1];
                            $bot->setStep(10);
                            $mode = $bot->getExtra();
                            switch ($mode) {
                                case 1:
                                    if ($activePoints >= 1400) {
                                        $bot->giveGift("-1400");
                                        $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(37), Null, $Msg->message_id);
                                        $bot->sendMsg($bot->admins[0], $bot->tMsg->get(38, [$Msg->text, 100000]), Null, Null, Null, true);
                                    } else
                                        $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(-5, [$activePoints, 1400]), Null, $Msg->message_id, Null, true);
                                    break;
                                case 2:
                                    if ($activePoints >= 2700) {
                                        $bot->giveGift("-2700");
                                        $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(37), Null, $Msg->message_id);
                                        $bot->sendMsg($bot->admins[0], $bot->tMsg->get(38, [$Msg->text, 200000]), Null, Null, Null, true);
                                    } else
                                        $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(-5, [$activePoints, 2700]), Null, $Msg->message_id, Null, true);
                                    break;
                                case 3:
                                    if ($activePoints >= 6700) {
                                        $bot->giveGift("-6700");
                                        $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(37), Null, $Msg->message_id);
                                        $bot->sendMsg($bot->admins[0], $bot->tMsg->get(38, [$Msg->text, 500000]), Null, Null, Null, true);
                                    } else
                                        $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(-5, [$activePoints, 6700]), Null, $Msg->message_id, Null, true);
                                    break;
                            }
                        }
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
            else if ($Msg->text === "🌱سنجد های من")
                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(4), Null, $Msg->message_id, Null, true);
            ////////////////////////////////////////////////////////////////////////////////////////////////////////
            else if ($Msg->text === "🛍تبدیل سنجد به...") {
                $bot->setStep(5);
                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(5), Null, $Msg->message_id, Null, true);
            } ////////////////////////////////////////////////////////////////////////////////////////////////////////
            else if ($Msg->text === "❓چجوری سنجد جمع کنم❓") {
                $bot->setStep(3);
                $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(3), Null, $Msg->message_id, Null, true);
            } ////////////////////////////////////////////////////////////////////////////////////////////////////////
            else
                if (empty($bot->getNumber())) {
                    $bot->setStep(16);
                    $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(16), Null, $Msg->message_id);
                } else
                    $bot->sendMsg($Msg->chat->id, $bot->tMsg->get(404, 1), Null, $Msg->message_id, Null, true);
        }
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
    }
} else if (isset($msg->callback_query)) {
    $Msg = $msg->callback_query;
    if (preg_match("/^payed:(.*)/", $Msg->data, $matches)) {
        $bot->sendMsg($matches[1], $bot->tMsg->get(39));
        $bot->sendMsg($Msg->message->chat->id, $bot->tMsg->get(40), Null, Null, Null, true);
    }
}
exit;
