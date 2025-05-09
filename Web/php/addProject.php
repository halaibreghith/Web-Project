<?php
require_once 'db.php.inc';
session_start();

function validateUploadedDocuments($files)
{
    $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'];
    $validatedFiles = [];

    foreach ($files['documents']['tmp_name'] as $key => $fileTmpPath) {
        $fileName = $files['documents']['name'][$key];
        $fileType = $files['documents']['type'][$key];

        // Check file type
        if (!in_array($fileType, $allowedTypes)) {
            throw new Exception("Unsupported file type: $fileType");
        }

        $validatedFiles[] = [
            'tmp_name' => $fileTmpPath,
            'name' => $fileName,
            'type' => $fileType
        ];
    }

    // Check if at most 3 documents are uploaded
    if (count($validatedFiles) > 3) {
        throw new Exception("You can upload up to 3 documents only.");
    }

    return $validatedFiles;
}

function uploadValidatedDocuments($projectId, $validatedFiles, $pdo)
{
    foreach ($validatedFiles as $file) {
        $fileName = "project{$projectId}_" . pathinfo($file['name'], PATHINFO_FILENAME) . "." . pathinfo($file['name'], PATHINFO_EXTENSION);
        $destPath = '../project-documents/' . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            throw new Exception("Error moving uploaded file to destination: $fileName");
        }

        // Save filename to database
        $stmt = $pdo->prepare("INSERT INTO project_documents (project_id, document_filename) VALUES (:project_id, :document_filename)");
        $stmt->execute([
            ':project_id' => $projectId,
            ':document_filename' => $fileName
        ]);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $projectId = $_POST['project_id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $customerName = $_POST['customer_name'];
        $totalBudget = $_POST['total_budget'];
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];

        $pdo->beginTransaction();

        // Insert project details into the database
        $stmt = $pdo->prepare("INSERT INTO projects (project_id, title, description, customer_name, budget, start_date, end_date) 
            VALUES (:project_id, :title, :description, :customer_name, :budget, :start_date, :end_date)");
        $stmt->execute([
            ':project_id' => $projectId,
            ':title' => $title,
            ':description' => $description,
            ':customer_name' => $customerName,
            ':budget' => $totalBudget,
            ':start_date' => $startDate,
            ':end_date' => $endDate
        ]);

        // Validate and upload documents
        if (!empty($_FILES['documents']['name'][0])) {
            $validatedFiles = validateUploadedDocuments($_FILES);
            uploadValidatedDocuments($projectId, $validatedFiles, $pdo);
        }

        $pdo->commit();

        $successMessage = "Project added successfully!";
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $errorMessage = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Project | Task Allocator Pro</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>

<body>
    <div class="container">
        <h1>Add Project</h1>
        <?php if (isset($successMessage)) : ?>
            <p class="message"><?php echo $successMessage; ?></p>
        <?php elseif (isset($errorMessage)) : ?>
            <p class="error-message"><?php echo $errorMessage; ?></p>
        <?php endif; ?>

        <form method="POST" action="add_project.php" enctype="multipart/form-data">
            <label for="project_id">Project ID:</label>
            <input type="text" id="project_id" name="project_id"  required><br>

            <label for="title">Project Title:</label>
            <input type="text" id="title" name="title" required><br>

            <label for="description">Project Description:</label>
            <textarea id="description" name="description"></textarea><br>

            <label for="customer_name">Customer Name:</label>
            <input type="text" id="customer_name" name="customer_name" required><br>

            <label for="total_budget">Total Budget:</label>
            <input type="number" id="total_budget" name="total_budget" required><br>

            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" required><br>

            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" required><br>

            <label for="documents">Upload Supporting Documents (max 3):</label>
            <input type="file" id="documents" name="documents[]" accept="application/pdf, image/jpeg, image/png, image/jpg" multiple><br>

            <input type="submit" value="Add Project">
        </form>
    </div>
</body>

</html>
