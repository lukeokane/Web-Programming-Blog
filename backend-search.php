<?php

require_once 'includes/configuration.php';

// Attempt search query execution
try
{
    if (isset($_REQUEST['term']))
    {
        // create prepared statement
        $sql = "SELECT * FROM blogs WHERE title LIKE :term OR userName LIKE :term OR content LIKE :term";
        $stmt = $db->prepare($sql);
        $term = '%' . $_REQUEST['term'] . '%';
        // bind parameters to statement
        $stmt->bindParam(':term', $term);
        // execute the prepared statement
        $stmt->execute();
        if ($stmt->rowCount() > 0)
        {
            while ($row = $stmt->fetch())
            {
                echo "<p><a href='view_blog.php?id=" . htmlspecialchars($row['ID'], ENT_QUOTES, 'utf-8') . "'>" . htmlspecialchars($row['title'], ENT_QUOTES, 'utf-8') . "</a></p>";
            }
        }
        else
        {
            echo "<p>No matches found";
        }
    }
} catch (PDOException $e)
{
    die("ERROR: Could not able to execute $sql. " . $e->getMessage());
}

// Close connection
unset($db);
?>
