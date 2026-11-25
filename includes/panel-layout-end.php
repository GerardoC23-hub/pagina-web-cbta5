    </main>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php foreach (($scripts ?? []) as $script): ?>
<script src="<?= $base ?>assets/js/<?= $script ?>"></script>
<?php endforeach; ?>
</body>
</html>
