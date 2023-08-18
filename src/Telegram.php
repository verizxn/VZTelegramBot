<?php

namespace verizxn\VZTelegramBot;

class Telegram {
    private $token = '';
    private $endpoint = 'https://api.telegram.org/bot';
    private $logs;
    
    /**
     * Method __construct
     *
     * @param $token $token Telegram bot token.
     * @param $logs $logs Specify logs directory for debug or false if you don't want to store logs.
     *
     * @return void
     */
    public function __construct($token, $logs = false){
        $this->token = $token;
        $this->logs = $logs;
    }
    
    /**
     * Method getUpdate
     * 
     * @return Array
     */
    public function getUpdate(){
        $update = file_get_contents('php://input');
        if($this->logs) file_put_contents("{$this->logs}/update.json", $update);
        $update = json_decode($update, true);
        return $update;
    }
        
    /**
     * Method generateInlineKeyboard
     *
     * @param $buttons $buttons InlineKeyboardButtons.
     *
     * @return JSON
     */
    public function generateInlineKeyboard($buttons){
        return json_encode(['inline_keyboard' => $buttons]);
    }
    
    /**
     * Method request
     *
     * @param $method $method Bot API method to request.
     * @param $parameters $parameters Parameters to pass.
     *
     * @return Array
     */
    public function request($method, $parameters){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "{$this->endpoint}{$this->token}/$method");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $request = curl_exec($ch);

        if($this->logs){
            file_put_contents("{$this->logs}/log.json", $request);
            file_put_contents("{$this->logs}/parameters.json", json_encode($parameters));
        }
        $request = json_decode($request, true);
        return $request;
    }
}