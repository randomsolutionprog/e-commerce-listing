<?php
session_start();

// Function to shorten words based on their lengths
function shortenWord($word) {
    $length = strlen($word);

    if ($length <= 3) {
        return $word; // If word has less than or equal to 3 characters, no need to shorten
    } elseif ($length <= 6) {
        return substr($word, 0, -1); // If word has 4 or 5 characters, shorten by 1
    } elseif ($length == 6) {
        return substr($word, 0, -2); // If word has 6 characters, shorten by 2
    } elseif ($length >= 7) {
        return substr($word, 0, -3) . '...'; // If word has 7 or more characters, shorten by 3 and add ellipsis
    } else {
        return $word; // For other cases, return the original word
    }
}

if (isset($_POST["search"])) {
    // Validate search input for unsafe characters
    if (preg_match('/[^A-Za-z0-9\s]/', $_POST["search_query"])) {
        // Redirect if search query contains unsafe characters
        header("Location: index.php");
        exit();
    }

    // Sanitize search input
    $search_query = trim($_POST["search_query"]);

    if (empty($search_query)) {
        // Redirect if search query is empty
        header("Location: index.php");
        exit();
    } else {
        // Database connection
        include("conn.php");

        // Check if the visitor exists by selecting visitorID
        if (isset($_COOKIE['random_name'])) {
            $randomName = $_COOKIE['random_name'];
        } else {
            // Redirect if visitor is not set
            header("Location: index.php?search=" . urlencode($search_query));
            exit();
        }

        // Select visitorID by visitor_name
        $stmt = $conn->prepare("SELECT visitorID FROM trace_visitor WHERE visitor_name = ?");
        $stmt->bind_param("s", $randomName);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            $visitorID = $row["visitorID"];

            // Update search_count if visitorID exists
            $words = explode(" ", $search_query);
            $count = min(count($words), 4); // Limit to 4 words
            $words = array_slice($words, 0, $count);

            foreach ($words as $word) {
                $word = strtolower($word);
                $conjunctions_and_names = ['and', 'or', 'but', 'the', 'a', 'an', 'of', 'in', 'on', 'at'];
                if (!in_array($word, $conjunctions_and_names)) {
                    $stmt = $conn->prepare("SELECT searchID, search_count FROM trace_search WHERE word LIKE ? OR word LIKE ?");
                    $tempWord = "%".$word."%";
                    $shortSpell = "%".shortenWord($word)."%";
                    $stmt->bind_param("ss", $tempWord, $shortSpell);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();

                    if (!$row) {
                        $stmt = $conn->prepare("INSERT INTO trace_search (visitorID, search_count, word) VALUES (?, 1, ?)");
                        $stmt->bind_param("is", $visitorID, $word);
                        $stmt->execute();
                    } else {
                        $searchID = $row['searchID'];
                        $search_count = $row['search_count'] + 1;
                        $stmt = $conn->prepare("UPDATE trace_search SET search_count = ? WHERE searchID = ?");
                        $stmt->bind_param("ii", $search_count, $searchID);
                        $stmt->execute();
                    }
                }
            }
        }

        // Redirect to index.php with sanitized values
        header("Location: index.php?search=" . urlencode($search_query));
        exit();
    }
}

// Redirect to index.php if search parameter is not set
header("Location: index.php");
exit();
?>
