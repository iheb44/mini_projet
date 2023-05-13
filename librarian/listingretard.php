<?php
include_once('../include/entete_front.inc.php');

$studentManager = new StudentManager("localhost", "bibliodb", "root", "");
$students = $studentManager->getUsersWithLateBooks();

?>
<html>
<head>
  <title>Biblio - etudiant</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../assets/css/style/listing.css">
</head>

<body>
<?php include('libNavBar.php'); ?>
  <div class="container">
    <h2>Notification des retardataires</h2>
    <div class="form-group">
      <input type="text" id="filter" class="form-control" placeholder="Rechercher par Titre">
    </div>
    <button type='button' class='btn btn-danger' onclick='submitNotificationAll()'>notifier All</button>
    <table id="myTable" class="table table-striped">
      <thead>
        <tr>

          <th>titre</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php

        foreach ($students as $row) {
          echo "<tr>
                      <td>{$row['book_title']}</td>
                      <td><button type='button' class='btn btn-danger' onclick='submitNotification({$row['user_id']},{$row['book_id']} , \"{$row['book_title']}\")'>notifier</button></td>
                  </tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <script>
    document.getElementById('filter').addEventListener('keyup', debound(filter_table, 500));

    function filter_table(e) {
      const rows = document.querySelectorAll('tbody tr')
      rows.forEach(row => {
        const titre = row.querySelector('td:nth-child(1)').innerText.toLowerCase()
        if (titre.includes(e.target.value.toLowerCase())) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      })
    }

    function debound(func, timeout) {
      let timer
      return (...args) => {
        if (!timer) {
          func.apply(this, args);
        }
        clearTimeout(timer)
        timer = setTimeout(() => {
          func.apply(this, args)
          timer = undefined
        }, timeout)
      }
    }

    function submitNotification(user_id, book_id, book_title) {
      $.ajax({
        type: 'POST',
        url: 'notif.php',
        data: {
          user_id: user_id,
          book_title: book_title,
          book_id: book_id,
        },
        success: function() {
          alert("notification envoyer avec success")
        },
        error: function(xhr, status, error) {
          console.error(error);
        }
      });
    }

    function submitNotificationAll() {
      $.ajax({
        type: 'POST',
        url: 'notifAll.php',
        success: function() {
          alert("notification envoyer avec success")
        },
        error: function(xhr, status, error) {
          console.error(error);
        }
      });
    }
  </script>
</body>

</html>