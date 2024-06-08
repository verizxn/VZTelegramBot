<?php

namespace verizxn\VZTelegramBot;

class Telegram {
    private $token = '';
    private $endpoint = 'https://api.telegram.org/bot';
    private $logs;
    private $session_path;
    
    /**
     * Method __construct
     *
     * @param $token $token Telegram bot token.
     * @param $logs $logs Specify logs directory for debug or false if you don't want to store logs.
     *
     * @return void
     */
    public function __construct($token, $logs = false, $session_path = false){
        $this->token = $token;
        $this->logs = $logs;
        $this->session_path = $session_path ? $session_path : getcwd()."/session.json";
    }
    
    /**
     * Method getWebhookUpdate
     * 
     * @return Array
     */
    public function getWebhookUpdate(){
        $update = file_get_contents('php://input');
        $this->log('update.json', json_encode($update));
        $update = json_decode($update, true);
        return $update;
    }

    /**
     * Method getUpdate
     * 
     * @return Array
     */
    public function getUpdate(){
        $result = $this->request('getUpdates', ['offset' => -1]);
        if(!isset($result['result'])) return false;
        $update = $result['result'];
        if(empty($update)) return false;
        $update = $update[0];
        if($this->parseUpdates($update['update_id'])) return false;
        $this->log('update.json', json_encode($update));
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

        $this->log('log.json', json_decode($request));
        $this->log('parameters.json', $parameters);
        $request = json_decode($request, true);
        return $request;
    }

    private function log($file, $content){
        if(!$this->logs) return;
        if(!file_exists($this->logs)) return;

        file_put_contents("{$this->logs}/$file", json_encode($content));
    }

    private function parseUpdates($update_id){
        if(!file_exists($this->session_path)) file_put_contents($this->session_path, '[]');
        $file = file_get_contents($this->session_path);
        $updates = json_decode($file, true);
        if(in_array($update_id, $updates)) return true;
        $updates[] = $update_id;
        $updates = json_encode($updates);
        file_put_contents($this->session_path, $updates);
        return false;
    }
}
