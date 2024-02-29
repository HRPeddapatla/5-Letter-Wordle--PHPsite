<?php
session_start();

// Function to reset the game
// Resets the target word, the user guesses array, #of guesses available, and the alphabet at the end
function resetGame() {
    unset($_SESSION['word']);
    unset($_SESSION['guesses']);
    unset($_SESSION['feedback']);
    unset($_SESSION['correct_letters']);
    unset($_SESSION['guesses_left']);
}

// Five-letter word list with a word from each letter of the alphabet
if (!isset($_SESSION['word'])) {
    $_SESSION['word'] = [
        "apple", "beach", "chair", "dance", "eagle", "flame", "giant", "horse", "image", "joker", 
        "knots", "lemon", "music", "nurse", "olive", "pearl", "queen", "roast", "snake", "truck", 
        "umbra", "vault", "water", "xenon", "yacht", "zebra"
    ];
    $word_index = rand(0, count($_SESSION['word']) - 1); // Randomly choose a word in the array
    $_SESSION['word'] = strtoupper($_SESSION['word'][$word_index]); // Convert the word to uppercase
    $_SESSION['guesses'] = array(); // Initialize guesses array
    $_SESSION['feedback'] = ''; // Initialize feedback
    $_SESSION['correct_letters'] = str_repeat('-', strlen($_SESSION['word'])); // Initialize correct letters
    $_SESSION['guesses_left'] = 6; // Initialize guesses left
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["restart"])) {
        resetGame(); // Restart the game if restart button is clicked
        header("Location: index.html"); // Redirect to landing page when game resets
        exit();
    }

    // PHP code to process the form submission
    define('MAX_ATTEMPTS', 6);
    $guess = strtoupper($_POST["guess"]); // Convert input to uppercase
    $_SESSION['guesses'][] = $guess; // Store previous guesses in session
    $num_attempts = count($_SESSION['guesses']); // Number of tries taken by the user
    $num_correct = 0; // Initialize variable for the number of correct letters

    // Check for correct letters and positions
    $correct_letters = $_SESSION['correct_letters'];
    for ($i = 0; $i < strlen($_SESSION['word']); $i++) {
        if ($guess[$i] == $_SESSION['word'][$i]) {
            $num_correct++;
            $correct_letters[$i] = $guess[$i];
        }
    }
    $_SESSION['correct_letters'] = $correct_letters;

    // Check for letters in incorrect positions
    $incorrect_positions = array();
    for ($i = 0; $i < strlen($_SESSION['word']); $i++) {
        if ($guess[$i] != $_SESSION['word'][$i] && in_array($guess[$i], str_split($_SESSION['word'])) && !in_array($guess[$i], $incorrect_positions)) {
            $incorrect_positions[] = $guess[$i];
        }
    }

    // Construct feedback message
    $incorrect_positions_string = implode(', ', $incorrect_positions);
    $_SESSION['feedback'] = "$correct_letters ($num_correct correct letter(s) and " . count($incorrect_positions) . " letter(s) in the wrong position: $incorrect_positions_string)";

    // Update guesses left
    $_SESSION['guesses_left'] = MAX_ATTEMPTS - $num_attempts;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Five-Letter Wordle Game</title>

    <!--Styling for the game page-->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            text-align: center;
        }
        form {
            margin-top: 50px;
        }
        input[type="text"] {
            padding: 10px;
            font-size: 16px;
            width: 200px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .correct {
            color: limegreen;
        }
        .incorrect {
            color: red;
        }
    </style>
</head>
<body>

<!--Display input field for guess retry-->
<div style="text-align: center; margin-top: 20px;">
    <h1>Five-Letter Wordle Game</h1>
    <p>Guess the five-letter word!</p>
    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
        <label for="guess">Enter your guess:</label><br>
        <input type="text" id="guess" name="guess" maxlength="<?= strlen($_SESSION['word']) ?>" pattern="[A-Za-z]{<?= strlen($_SESSION['word']) ?>}" required autofocus style="margin-bottom: 10px;"><br>
        <input type="submit" value="Submit Guess">
    </form>
</div>


<!--Display restart button-->
<div style="text-align: center; margin-top: 20px;">
    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" style="margin-top: -10px;">
        <input type="submit" name="restart" value="Restart Game">
    </form>
</div>

<!--Display current guess-->
<div>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($guess)) {
        echo "<p><span style='text-decoration: underline;'>Current guess:</span> $guess</p>";
    }
    ?>
</div>

<!--Display guesses left-->
<div>
    <p><span style="text-decoration: underline;">Guesses left:</span> <?= $_SESSION['guesses_left'] ?></p>
</div>

<!--Display feedback-->
<div>
    <?php
    if (!empty($_SESSION['feedback'])) {
        echo "<p><span style='text-decoration: underline;'>Feedback:</span> {$_SESSION['feedback']}</p>";
    }
    ?>
</div>

<!--Display previous guesses-->
<div>
    <p style="text-decoration: underline;">Previous guesses:</p>
    <div>
        <?php
        if (!empty($_SESSION['guesses'])) {
            echo implode('<br>', $_SESSION['guesses']);
        }
        ?>
    </div>
</div>

<!--Display alphabet-->
<div style="margin-top: 20px;">
    <p style="text-decoration: underline;">Alphabet:</p>
    <div style="font-size: 30px;">
        <?php
        $alphabet = range('A', 'Z');
        foreach ($alphabet as $letter) {
                // If letter is in the user guess and target, then highlight green
            if ((strpos($guess, $letter) !== false) && (strpos($_SESSION['word'], $letter) !== false)) { 
                echo " <span class='correct' style='font-weight: bold;'>$letter</span> ";
            }   // If letter is in the user guess, but not the target word, then highlight red
            else if((strpos($guess, $letter) !== false) && (strpos($_SESSION['word'], $letter) == false)) {
                echo " <span class='incorrect' style='font-weight: bold;'>$letter</span> ";
            }   // If letter doesn't fall either of these conditions then leave in black text
            else {
                echo " <span style='font-weight: bold;'>$letter</span> ";
            }
        }
        ?>
    </div>
</div>

<?php
// Print result
if (isset($num_correct) && $num_correct == strlen($_SESSION['word'])) {
    echo "<div><p>Congratulations! You guessed the word \"{$_SESSION['word']}\" in $num_attempts attempt(s).</p></div>";
    resetGame(); // Reset the game after successful guess
} elseif ($num_attempts >= MAX_ATTEMPTS) {
    echo "<div><p>Sorry, you failed to guess the word \"{$_SESSION['word']}\" in " . MAX_ATTEMPTS . " attempt(s).</p></div>";
    resetGame(); // Reset the game after reaching maximum attempts
}
?>

</body>
</html>