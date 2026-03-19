
    </div><!-- /.admin-content -->
</div><!-- /.admin-main -->
</div><!-- /.admin-layout -->
<script>
// Table search
document.querySelectorAll('[data-table-search]').forEach(input => {
    const targetId = input.dataset.tableSearch;
    const tbody = document.querySelector('#' + targetId + ' tbody');
    if (!tbody) return;
    input.addEventListener('input', () => {
        const q = input.value.toLowerCase();
        tbody.querySelectorAll('tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
});

// Confirm deletes
document.querySelectorAll('[data-confirm]').forEach(btn => {
    btn.addEventListener('click', e => {
        if (!confirm(btn.dataset.confirm || 'Are you sure?')) e.preventDefault();
    });
});
</script>
</body>
</html>
