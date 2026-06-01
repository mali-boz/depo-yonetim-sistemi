<?php
/**
 * footer.php — Ortak sayfa altlığı
 */
?>
</main> <!-- Ana İçerik Konteyneri Kapanışı (header.php'den) -->

<footer class="mt-5 py-4 border-top bg-white text-center text-muted">
    <div class="container">
        <small>&copy; <?= date('Y') ?> Depo Yönetim Sistemi. Tüm hakları saklıdır.</small>
    </div>
</footer>

<!-- Silme Onay Modalı (Tüm sayfalarda kullanılabilir) -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content border-0 shadow">
      <form method="POST" id="deleteForm" action="">
        <div class="modal-header bg-danger text-white border-0">
          <h5 class="modal-title fs-6" id="deleteModalLabel"><i class="bi bi-exclamation-triangle"></i> Silme Onayı</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
        </div>
        <div class="modal-body text-center py-4">
          <p class="mb-0">Bu kaydı silmek istediğinize emin misiniz?<br><small class="text-muted">Bu işlem geri alınamaz.</small></p>
          <input type="hidden" name="id" id="deleteRecordId" value="">
        </div>
        <div class="modal-footer bg-light border-0 justify-content-center">
          <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">İptal</button>
          <button type="submit" class="btn btn-danger btn-sm px-4">Evet, Sil</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Bootstrap Native Form Doğrulama (G1)
(function () {
  'use strict'
  var forms = document.querySelectorAll('.needs-validation')
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
        form.classList.add('was-validated')
      }, false)
    })
})()

// Dinamik Silme Modalı (G3)
document.addEventListener('DOMContentLoaded', function() {
    var deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var recordId = button.getAttribute('data-id');
            var actionUrl = button.getAttribute('data-action');
            
            var modalForm = deleteModal.querySelector('#deleteForm');
            var modalInputId = deleteModal.querySelector('#deleteRecordId');
            
            modalForm.setAttribute('action', actionUrl);
            modalInputId.value = recordId;
        });
    }

    // Tablo Arama Fonksiyonu
    var searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            var filter = this.value.toLowerCase().trim();
            var table = document.querySelector('.table tbody');
            if (!table) return;
            var rows = table.querySelectorAll('tr');
            rows.forEach(function(row) {
                var text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    }
});
</script>
</body>
</html>
