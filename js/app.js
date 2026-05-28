document.addEventListener('DOMContentLoaded', function() {

    const form = document.getElementById('taskForm');
    const searchInput = document.getElementById('searchInput');
    const priorityFilter = document.getElementById('priorityFilter');
    const tableBody = document.querySelector('#tasksTable tbody');
    const editForm = document.getElementById('editTaskForm');
    const editTaskId = document.getElementById('editTaskId');
    const editTaskTitle = document.getElementById('editTaskTitle');
    const editTaskPriority = document.getElementById('editTaskPriority');
    const editTaskDescription = document.getElementById('editTaskDescription');
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    function applyFilters() {
        const term = searchInput.value.toLowerCase();
        const selectedPriority = priorityFilter.value;
        const rows = tableBody.getElementsByTagName('tr');

        for (let row of rows) {
            const title = row.cells[0].textContent.toLowerCase();
            const rowPriority = row.dataset.priority || '';
            const matchesSearch = title.includes(term);
            const matchesPriority = selectedPriority === 'all' || rowPriority === selectedPriority;
            row.style.display = matchesSearch && matchesPriority ? '' : 'none';
        }
    }

    // Live Search
    searchInput.addEventListener('keyup', function() {
        applyFilters();
    });

    // Priority Filter
    priorityFilter.addEventListener('change', function() {
        applyFilters();
    });

    // Form Submit with Loading
    form.addEventListener('submit', function(e) {
        const btn = document.getElementById('submitBtn');
        const title = form.querySelector('input[name="title"]').value.trim();

        if(title.length < 3) {
            alert("Title must be at least 3 characters!");
            e.preventDefault();
            return;
        }

        btn.innerHTML = 'Adding...';
        btn.disabled = true;
    });

    // Populate edit modal
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('tr');
            editTaskId.value = row.dataset.id;
            editTaskTitle.value = row.dataset.title || '';
            editTaskPriority.value = row.dataset.priority || 'Medium';
            editTaskDescription.value = row.dataset.description || '';
        });
    });

    // Complete Task (POST with CSRF)
    document.querySelectorAll('.update-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('tr');
            const id = row.dataset.id;

            if(confirm('Mark this task as Completed?')) {
                fetch('actions/update-task.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${encodeURIComponent(id)}&csrf=${encodeURIComponent(csrf)}`
                })
                .then(res => res.text())
                .then(() => location.reload());
            }
        });
    });

    // Edit Task Submit
    editForm.addEventListener('submit', function(e) {
        const title = editTaskTitle.value.trim();
        if (title.length < 3) {
            alert('Title must be at least 3 characters!');
            e.preventDefault();
            return;
        }

        e.preventDefault();
        const payload = new URLSearchParams(new FormData(editForm));

        fetch('actions/edit-task.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: payload.toString()
        })
        .then(res => res.text().then(text => ({ ok: res.ok, status: res.status, text })))
        .then(({ ok, text }) => {
            if (!ok) {
                alert(text || 'Failed to update task');
                return;
            }
            location.reload();
        });
    });

    // Delete Task (POST with CSRF)
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('tr');
            const id = row.dataset.id;

            if(confirm('Are you sure you want to delete this task?')) {
                fetch('actions/delete-task.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${encodeURIComponent(id)}&csrf=${encodeURIComponent(csrf)}`
                })
                .then(res => res.text())
                .then(() => {
                    row.remove();
                });
            }
        });
    });
});