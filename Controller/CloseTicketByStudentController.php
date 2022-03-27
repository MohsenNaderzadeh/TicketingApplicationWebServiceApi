<?php
/**
 * Created by PhpStorm.
 * User: Mohsen
 * Date: 5/22/2021
 * Time: 7:09 PM
 */
include_once $_SERVER['DOCUMENT_ROOT'] . "/ContactUs/Controller/BaseController.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/ContactUs/Model/Repo/UserRepo.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/ContactUs/Model/Repo/TicketRepo.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/ContactUs/Model/Constants/Constants.php";

class CloseTicketByStudentController extends BaseController
{


    public function closeTicket($ticketId,$token)
    {
        $userRepo=new UserRepo();
        $ticketRepo=new TicketRepo();

        if($userRepo->checkTokenAvailability($token))
        {
            if($ticketRepo->checkTicketIdisValid($ticketId))
            {
                if($ticketRepo->updateTicketStatus($ticketId,Constants::TICKET_CLOSED_BY_STUDENT)){
                    header("Content-Type:application/json");
                    $updatedTicket=$ticketRepo->getTicket($ticketId);
                    if($updatedTicket!=false)
                    {
                        $result["success"]=true;
                        $temp["TicketId"]=$updatedTicket->getTicketId();
                        $temp["TicketTitle"]=$updatedTicket->getTicketTitle();
                        $temp["TicketRelatedAdministrativeDepartemantId"]=$updatedTicket->getTicketRelatedAdministrativeDepartemantId();
                        $temp["TicketOwnerId"]=$updatedTicket->getTicketOwnerId();
                        $temp["TicketSubmitDate"]=$updatedTicket->getTicketSubmitDate();
                        $temp["TicketStatus"]=$updatedTicket->getTicketStatus();
                        $result["updatedTicket"]=$temp;
                        echo json_encode($result);
                    }
                }else
                {
                    header("Content-Type:application/json");
                    http_response_code(500);
                    $responseResult["success"] = false;
                    $responseResult["message"] = "خطا در انجام عملیات";
                    echo json_encode($responseResult);
                }
            }else
            {
                header("Content-Type:application/json");
                http_response_code(500);
                $responseResult["success"] = false;
                $responseResult["message"] = "خطا در انجام عملیات";
                echo json_encode($responseResult);
            }

        }else
        {
            header("Content-Type:application/json");
            http_response_code(401);
            $result["success"] = false;
            $result["message"]="لطفا مجددا به حساب کاربری خود وارد شوید";
            echo json_encode($result);
        }
    }
}