import './bootstrap';
import Sortable from 'sortablejs';
import Alpine from 'alpinejs';

window.Sortable = Sortable;
window.Alpine = Alpine;

Alpine.start();

// Initialize Kanban Board when DOM is ready
document.addEventListener('DOMContentLoaded', function () {
    initializeKanban();
});

function initializeKanban() {
    const columns = document.querySelectorAll('.kanban-column');
    
    // Only initialize if kanban columns exist
    if (columns.length === 0) return;

    columns.forEach(column => {
        new Sortable(column, {
            group: 'kanban',
            animation: 200,
            ghostClass: 'opacity-50',
            handle: '.task-card',
            
            onEnd: function (evt) {
                const taskId = evt.item.dataset.taskId;
                const newStatus = evt.to.dataset.status;
                const oldStatus = evt.from.dataset.status;

                if (newStatus !== oldStatus) {
                    updateTaskStatusAjax(taskId, newStatus);
                }
            }
        });
    });
}

// AJAX update function
async function updateTaskStatusAjax(taskId, newStatus) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    try {
        const response = await fetch(`/tasks/${taskId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ status: newStatus })
        });

        const data = await response.json();

        if (data.success) {
            showToast('Task status updated!');
            updateColumnCounts();
        } else {
            showToast('Failed to update task');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Error updating task');
    }
}

// Update column counts after drag
function updateColumnCounts() {
    document.querySelectorAll('.kanban-column').forEach(column => {
        const count = column.querySelectorAll('.task-card').length;
        const badge = column.parentElement.querySelector('span');
        if (badge) badge.textContent = count;
    });
}

// Toast notification
function showToast(message) {
    const toast = document.getElementById('toast');
    if (!toast) return;
    
    const toastMessage = document.getElementById('toast-message');
    if (toastMessage) toastMessage.textContent = message;

    toast.classList.remove('translate-y-20', 'opacity-0');
    
    setTimeout(() => {
        toast.classList.add('translate-y-20', 'opacity-0');
    }, 2500);
}

// ============================================
// Notification AJAX Handler (Real-time)
// ============================================
document.addEventListener('DOMContentLoaded', function () {
    const notificationButtons = document.querySelectorAll('.ajax-notification-btn');

    notificationButtons.forEach(button => {
        button.addEventListener('click', async function (e) {
            e.preventDefault();

            const form = this.closest('.ajax-notification-form');
            if (!form) return;

            const csrfToken = form.querySelector('input[name="_token"]').value;
            const actionUrl = form.action;

            try {
                const response = await fetch(actionUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ _method: 'POST' }),
                });

                const data = await response.json();

                if (data.success) {
                    // Update badge (real-time)
                    updateNotificationBadge(data.unread_count);

                    // Redirect to task page
                    if (data.url) {
                        window.location.href = data.url;
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                // Fallback: normal form submit
                form.submit();
            }
        });
    });
});

// Helper: Update notification badge count
function updateNotificationBadge(count) {
    const badge = document.getElementById('notification-badge');

    if (!badge) return;

    if (count > 0) {
        badge.textContent = count;
        badge.style.display = 'flex';
    } else {
        badge.style.display = 'none';
        badge.remove();
    }
}