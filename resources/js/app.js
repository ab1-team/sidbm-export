
import Alpine from 'alpinejs';
import { Chart, CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend, Filler } from 'chart.js';

Chart.register(CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend, Filler);

window.Alpine = Alpine;
window.Chart = Chart;

Alpine.data('notificationDropdown', () => ({
    open: false,
    notifications: [],
    unreadCount: 0,
    loading: false,

    init() {
        this.fetchNotifications();
        this.startPolling();
    },

    get displayCount() {
        if (this.unreadCount > 99) return '99+';
        return this.unreadCount;
    },

    async fetchNotifications() {
        this.loading = true;
        try {
            const res = await fetch('/api/notifications', {
                credentials: 'include'
            });
            const data = await res.json();
            if (data.success && data.notifications) {
                this.notifications = data.notifications;
                this.unreadCount = this.notifications.filter(n => !n.pivot?.read_at).length;
            }
        } catch (e) {
            console.error('Failed to fetch notifications:', e);
        } finally {
            this.loading = false;
        }
    },

    async markAsRead(id) {
        try {
            await fetch(`/api/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                credentials: 'include'
            });
            const notif = this.notifications.find(n => n.id === id);
            if (notif && !notif.pivot?.read_at) {
                notif.pivot = notif.pivot || {};
                notif.pivot.read_at = new Date().toISOString();
                this.unreadCount = Math.max(0, this.unreadCount - 1);
            }
        } catch (e) {
            console.error('Failed to mark as read:', e);
        }
    },

    async markAllAsRead() {
        try {
            await fetch('/api/notifications/read-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                credentials: 'include'
            });
            this.notifications.forEach(n => {
                n.pivot = n.pivot || {};
                n.pivot.read_at = new Date().toISOString();
            });
            this.unreadCount = 0;
        } catch (e) {
            console.error('Failed to mark all as read:', e);
        }
    },

    async deleteNotification(id) {
        try {
            await fetch(`/api/notifications/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                credentials: 'include'
            });
            this.notifications = this.notifications.filter(n => n.id !== id);
            this.unreadCount = Math.max(0, this.unreadCount - 1);
        } catch (e) {
            console.error('Failed to delete notification:', e);
        }
    },

    startPolling() {
        setInterval(() => {
            if (!this.open) {
                this.fetchNotifications();
            }
        }, 30000);
    },

    formatTime(dateStr) {
        if (!dateStr) return '';
        const date = new Date(dateStr);
        const now = new Date();
        const diff = now - date;
        if (diff < 60000) return 'Baru saja';
        if (diff < 3600000) return `${Math.floor(diff / 60000)} menit lalu`;
        if (diff < 86400000) return `${Math.floor(diff / 3600000)} jam lalu`;
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
    },

    get isUnread() {
        return (notif) => !notif.pivot?.read_at;
    }
}));

Alpine.start();
