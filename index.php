<?php
    $name = "";
    $character = "";
    $email = "";
    $birth_year = 1969;
    $validation_error = "";
    $existing_users = ["admin", "guest"];

    function consoleLog($data) {
      $output = $data;
      if (is_array($output))
          $output = implode(',', $output);
      echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
    }

    function removeSpaces($string) {
      $pattern = "/\s/";
      return preg_replace($pattern, "", $string);
    }

    function isNewUser($username) {
      global $existing_users;
      if (in_array($username, $existing_users)) {
        return FALSE;
      } else {
        return TRUE;
      }
    }

    function checkCharacter($character) {
      if (in_array($character, ["wizard", "mage", "orc"])) {
        return TRUE;
      } else {
        return FALSE;
      }
    }

    function checkEmailAddress($email_address) {
      $filtered_address = filter_var($email_address, FILTER_VALIDATE_EMAIL);
      return $filtered_address;
    }

    function checkBirthYear($year) {
      $options = ["options" => [
        "min_range" => 1900,
        "max_range" => date('Y')
      ]];
      if (filter_var($year, FILTER_VALIDATE_INT, $options)) {
        return TRUE;
      } else {
        return FALSE;
      }
    }


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $raw_name = $_POST["name"];
      $raw_character = $_POST["character"];
      $raw_email = $_POST["email"];
      $raw_birth_year = $_POST["birth_year"];

      // Validate the username
      $raw_name = removeSpaces(
          htmlentities($raw_name));

      if (isNewUser($raw_name)) {
        $name = $raw_name;
      } else {
        $validation_error .= "This name is taken. <br>";
      }

      // Validate the character
      if (checkCharacter($raw_character)) {
        $character = $raw_character;
      } else {
        $validation_error .= "You must pick a wizard, mage, or orc. <br>";
      }

      // Validate the email
      $filtered_address = checkEmailAddress($raw_email);
      if ($filtered_address) {
        $email = $filtered_address;
      } else {
        $validation_error .= "Invalid email. <br>";
      }

      // Validate the year
      $year_int = intval($raw_birth_year);
      if (checkBirthYear($year_int)) {
        $birth_year = $year_int;
      } else {
        $validation_error .= "That can't be your birth year. <br>";
      }
    }
?>

<html>

  <body>

    <h1>Create your profile</h1>

    <form method="post" action="">
      <p>Select a name: 
        <input 
          type="text" 
          name="name" 
          value="<?php echo $name;?>"
        >
      </p>
    
      <p>Select a character:
        <input 
          type="radio" 
          name="character" 
          value="wizard" 
          <?php echo ($character=='wizard')?'checked':'' ?>
        > Wizard
    
        <input 
          type="radio" 
          name="character" 
          value="mage" 
          <?php echo ($character=='mage')?'checked':'' ?>
        > Mage
    
        <input 
          type="radio" 
          name="character" 
          value="orc" 
          <?php echo ($character=='orc')?'checked':'' ?>
        > Orc
      </p>
    
      <p>Enter an email:
        <input 
          type="text" 
          name="email" 
          value="<?php echo $email;?>" 
        >
      </p>
    
      <p>Enter your birth year:
        <input 
          type="text" 
          name="birth_year" 
          value="<?php echo $birth_year;?>"
        >
      </p>
    
      <p>
        <span style="color:red;">
          <?= $validation_error;?>
        </span>
      </p>
    
      <input type="submit" value="Submit">
    </form>
      
    <h2>Preview:</h2>
    
    <p>Name: <?=$name;?></p>
    <p>Character Type: <?=$character;?></p>
    <p>Email: <?=$email;?></p>
    <p>Age: <?=date("Y")-$birth_year;?></p>
    
  </body>
  
</html>
