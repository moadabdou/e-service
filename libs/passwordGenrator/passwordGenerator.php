<?php 

class PasswordGenerator {
    private $length;
    private $includeUppercase;
    private $includeNumbers;
    private $includeSpecialChars;

    public function __construct($length = 12, $includeUppercase = true, $includeNumbers = true, $includeSpecialChars = true) {
        $this->length = $length;
        $this->includeUppercase = $includeUppercase;
        $this->includeNumbers = $includeNumbers;
        $this->includeSpecialChars = $includeSpecialChars;
    }

    public function generate() {
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $specialChars = '!@#$%^&*()-_=+[]{}<>?/';
        
        $characters = $lowercase;
        
        if ($this->includeUppercase) {
            $characters .= $uppercase;
        }
        if ($this->includeNumbers) {
            $characters .= $numbers;
        }
        if ($this->includeSpecialChars) {
            $characters .= $specialChars;
        }
        
        $password = '';
        $charLength = strlen($characters);
        
        for ($i = 0; $i < $this->length; $i++) {
            $password .= $characters[random_int(0, $charLength - 1)];
        }
        
        return $password;
    }
}

?>