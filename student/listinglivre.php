<?php
include_once('../include/entete_front.inc.php');

$bookList = new BookList("localhost", "bibliodb", "root", "");
$books = $bookList->getBooks();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['emprunter'])) {
        $livre_id = $_POST['livre_id'];
        $user_id = $_SESSION['user_id'];
        $return_date = $_POST['return_date'];
        $emprunter_success = $bookList->emprunterStudent($user_id, $livre_id, $return_date);
    }
}
?>
<html lang="fr">
<head>
    <title>Biblio - Livres</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../assets/css/style/listing.css">
</head>
<body>
<?php include('studentNavbar.php'); ?>
<div class="container">
    <h2>Livres</h2>
    <div class="form-group row">
        <div class="col-sm-10">
            <input type="text" id="filter" class="form-control" placeholder="Rechercher par titre, auteur ou genre">
        </div>
    </div>
    <div class="table-responsive">
        <table id="myTable" class="table table-striped w-100" width="100">
            <thead>
            <tr>
                <th>Titre</th>
                <th class="text-center">Auteur</th>
                <th class="text-center">Genre</th>
                <th class="text-center">Disponibilité</th>
                <th class="text-center">Image</th>
                <th class="text-center">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($books as $book) {
                $button = '';
                if ($book['copies_disponibles'] > 0) {
                    $button = "<button type='button' class='btn btn-danger emprunter' data-toggle='modal' data-target='#myModal' data-id='{$book['id']}' data-titre='{$book['titre']}' data-auteur='{$book['auteur']}' data-genre='{$book['genre']}' data-copies='{$book['copies_disponibles']}'>Emprunter</button>";
                }
                ?>
                <tr>
                    <td><?php echo $book['titre']; ?></td>
                    <td class="text-center"><?php echo $book['auteur']; ?></td>
                    <td class="text-center"><?php echo $book['genre']; ?></td>
                    <?php
                    if ($book['copies_disponibles'] == 0) {
                        echo "<td class='bg-danger text-white text-center' width='5'> Non disponible </td>";
                    } else {
                        echo "<td class='bg-success text-white text-center' width='5'> Disponible </td>";
                    }
                    ?>
                    <td class="text-center" style="width: 10%">
                        <img class="book-image" src="../assets/images/couvertures/<?php echo $book['image_url']; ?>"
                             onerror="this.onerror=null; this.src='../assets/images/image_non_disponible.png';"
                             alt="Image de <?php echo $book['titre']; ?>">
                    </td>
                    <td class="text-center"><?php echo $button ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h4 class="modal-title">Emprunter</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="">
                        <input type="hidden" name="livre_id" id="livre_id" value="">
                        <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
                        <div class="form-group">
                            <label for="return_date">Date de retour:</label>
                            <input type="date" class="form-control" name="return_date" id="return_date"
                                   value="<?= date('Y-m-d', strtotime('+10 days')) ?>" required readonly>
                        </div>
                        <button type="submit" class="btn btn-primary" name="emprunter">Valider</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
<script>
    $(document).ready(function () {
        const filterInput = $("#filter");
        const tableRows = $("#myTable tbody tr");

        function filterRows() {
            const filterValue = filterInput.val().toLowerCase();
            tableRows.each(function () {
                const title = $(this).find("td:nth-child(2)").text().toLowerCase();
                const author = $(this).find("td:nth-child(3)").text().toLowerCase();
                const genre = $(this).find("td:nth-child(4)").text().toLowerCase();
                if (title.includes(filterValue) || author.includes(filterValue) || genre.includes(filterValue)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        filterInput.on("keyup", function () {
            filterRows();
        });

        $(".emprunter").click(function () {
            const livre_id = $(this).data("id");
            $("#livre_id").val(livre_id);
            $('#myModal').modal('show');
        });
    });

</script>

</body>

</html>

