<!-- 
  HotspotDistanceCache.vue Component
  Location: src/components/HotspotDistanceCache.vue
  
  Features:
  - List cache entries with pagination
  - Search by from/to hotspot names
  - Filter by hotspot ID and travel type
  - Create new cache entry
  - Edit existing cache entry
  - Delete single or bulk entries
  - Export to Excel
  - Sidebar integration under hotspots section
-->

<template>
  <div class="hotspot-distance-cache-container">
    <!-- Header -->
    <div class="cache-header">
      <h2 class="page-title">Hotspot Distance Cache</h2>
      <div class="header-actions">
        <button class="btn btn-primary" @click="openCreateModal">
          <i class="icon-plus"></i> Add Cache Entry
        </button>
        <button class="btn btn-success" @click="exportToExcel" :disabled="loading">
          <i class="icon-download"></i> Export Excel
        </button>
      </div>
    </div>

    <!-- Search & Filters -->
    <div class="filters-section">
      <div class="filter-row">
        <div class="filter-group">
          <label>Search (Name)</label>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search by hotspot name..."
            class="form-control"
            @input="onSearchChange"
          />
        </div>

        <div class="filter-group">
          <label>From Hotspot</label>
          <select v-model="filters.fromHotspotId" class="form-control" @change="applyFilters">
            <option value="">All</option>
            <option v-for="hs in hotspots" :key="hs.id" :value="hs.id">
              {{ hs.name }}
            </option>
          </select>
        </div>

        <div class="filter-group">
          <label>To Hotspot</label>
          <select v-model="filters.toHotspotId" class="form-control" @change="applyFilters">
            <option value="">All</option>
            <option v-for="hs in hotspots" :key="hs.id" :value="hs.id">
              {{ hs.name }}
            </option>
          </select>
        </div>

        <div class="filter-group">
          <label>Travel Type</label>
          <select v-model="filters.travelLocationType" class="form-control" @change="applyFilters">
            <option value="">All Types</option>
            <option value="1">Local</option>
            <option value="2">Outstation</option>
          </select>
        </div>

        <div class="filter-group">
          <label>&nbsp;</label>
          <button class="btn btn-secondary" @click="resetFilters">Clear Filters</button>
        </div>
      </div>
    </div>

    <!-- Data Table -->
    <div class="table-section" v-if="!loading">
      <div class="table-header">
        <div class="table-info">
          <span class="record-count">{{ list.total }} entries</span>
          <span v-if="selectedIds.length > 0" class="selected-count">
            {{ selectedIds.length }} selected
          </span>
        </div>
        <div class="table-actions" v-if="selectedIds.length > 0">
          <button class="btn btn-danger btn-sm" @click="bulkDelete">
            Delete Selected ({{ selectedIds.length }})
          </button>
        </div>
      </div>

      <table class="data-table">
        <thead>
          <tr>
            <th width="40">
              <input type="checkbox" v-model="selectAll" @change="toggleSelectAll" />
            </th>
            <th width="60">ID</th>
            <th>From Hotspot</th>
            <th>To Hotspot</th>
            <th width="80">Type</th>
            <th width="100">Distance</th>
            <th width="80">Speed</th>
            <th width="90">Travel Time</th>
            <th width="100">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="list.rows.length === 0">
            <td colspan="9" class="text-center">No cache entries found</td>
          </tr>
          <tr v-for="row in list.rows" :key="row.id" :class="{ selected: selectedIds.includes(row.id) }">
            <td>
              <input type="checkbox" v-model="selectedIds" :value="row.id" />
            </td>
            <td>{{ row.id }}</td>
            <td>
              <div class="hotspot-cell">
                <strong>{{ row.fromHotspotId }}</strong><br />
                <small>{{ row.fromHotspotName }}</small>
              </div>
            </td>
            <td>
              <div class="hotspot-cell">
                <strong>{{ row.toHotspotId }}</strong><br />
                <small>{{ row.toHotspotName }}</small>
              </div>
            </td>
            <td>
              <span v-if="row.travelLocationType === 1" class="badge badge-info">Local</span>
              <span v-else class="badge badge-warning">Outstation</span>
            </td>
            <td class="text-right">{{ Number(row.distanceKm).toFixed(2) }} km</td>
            <td class="text-right">{{ Number(row.speedKmph).toFixed(0) }} km/h</td>
            <td>{{ row.travelTime }}</td>
            <td>
              <div class="action-buttons">
                <button class="btn btn-sm btn-info" @click="editEntry(row)" title="Edit">
                  <i class="icon-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger" @click="deleteEntry(row.id)" title="Delete">
                  <i class="icon-trash"></i>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="pagination">
        <button
          class="btn btn-sm"
          @click="previousPage"
          :disabled="list.page <= 1"
        >
          Previous
        </button>
        <span class="page-info">Page {{ list.page }} of {{ list.pages }}</span>
        <button
          class="btn btn-sm"
          @click="nextPage"
          :disabled="list.page >= list.pages"
        >
          Next
        </button>
      </div>
    </div>

    <!-- Loading State -->
    <div class="loading-state" v-if="loading">
      <div class="spinner"></div>
      <p>Loading cache entries...</p>
    </div>

    <!-- Create/Edit Modal -->
    <div class="modal" v-if="showModal" @click.self="closeModal">
      <div class="modal-content">
        <div class="modal-header">
          <h3>{{ editingId ? 'Edit' : 'Create' }} Cache Entry</h3>
          <button class="btn-close" @click="closeModal"></button>
        </div>

        <div class="modal-body">
          <form @submit.prevent="saveEntry">
            <div class="form-group">
              <label>From Hotspot *</label>
              <select v-model.number="form.fromHotspotId" class="form-control" required>
                <option value="">Select hotspot</option>
                <option v-for="hs in hotspots" :key="hs.id" :value="hs.id">
                  {{ hs.name }} (ID: {{ hs.id }})
                </option>
              </select>
            </div>

            <div class="form-group">
              <label>To Hotspot *</label>
              <select v-model.number="form.toHotspotId" class="form-control" required>
                <option value="">Select hotspot</option>
                <option v-for="hs in hotspots" :key="hs.id" :value="hs.id">
                  {{ hs.name }} (ID: {{ hs.id }})
                </option>
              </select>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>Travel Type *</label>
                <select v-model.number="form.travelLocationType" class="form-control" required>
                  <option value="1">Local</option>
                  <option value="2">Outstation</option>
                </select>
              </div>

              <div class="form-group">
                <label>Speed (km/h) *</label>
                <input v-model.number="form.speedKmph" type="number" class="form-control" required />
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>Haversine (km) *</label>
                <input v-model.number="form.haversineKm" type="number" step="0.0001" class="form-control" required />
              </div>

              <div class="form-group">
                <label>Correction Factor *</label>
                <input v-model.number="form.correctionFactor" type="number" step="0.001" class="form-control" required />
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>Distance (km) *</label>
                <input v-model.number="form.distanceKm" type="number" step="0.0001" class="form-control" required />
              </div>

              <div class="form-group">
                <label>Travel Time (HH:MM:SS) *</label>
                <input v-model="form.travelTime" type="text" placeholder="00:30:00" class="form-control" required />
              </div>
            </div>

            <div class="form-group">
              <label>Method</label>
              <input v-model="form.method" type="text" class="form-control" value="HAVERSINE" />
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="closeModal">Cancel</button>
              <button type="submit" class="btn btn-primary">{{ editingId ? 'Update' : 'Create' }}</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'HotspotDistanceCache',
  data() {
    return {
      list: {
        total: 0,
        page: 1,
        pages: 1,
        rows: [],
      },
      hotspots: [],
      travelTypes: [],
      searchQuery: '',
      filters: {
        fromHotspotId: null,
        toHotspotId: null,
        travelLocationType: null,
      },
      selectedIds: [],
      selectAll: false,
      loading: false,
      showModal: false,
      editingId: null,
      form: {
        fromHotspotId: null,
        toHotspotId: null,
        travelLocationType: 1,
        haversineKm: 0,
        correctionFactor: 1.5,
        distanceKm: 0,
        speedKmph: 40,
        travelTime: '00:00:00',
        method: 'HAVERSINE',
      },
    };
  },

  mounted() {
    this.loadFormOptions();
    this.loadList();
  },

  methods: {
    async loadFormOptions() {
      try {
        const response = await fetch('/api/v1/hotspot-distance-cache/form-options');
        const data = await response.json();
        this.hotspots = data.hotspots;
        this.travelTypes = data.travelTypes;
      } catch (error) {
        console.error('Error loading form options:', error);
      }
    },

    async loadList() {
      this.loading = true;
      try {
        const params = new URLSearchParams({
          page: this.list.page,
          size: 50,
          search: this.searchQuery,
          ...Object.fromEntries(Object.entries(this.filters).filter(([, v]) => v !== null && v !== '')),
        });

        const response = await fetch(`/api/v1/hotspot-distance-cache?${params}`);
        const data = await response.json();
        this.list = data;
        this.selectedIds = [];
        this.selectAll = false;
      } catch (error) {
        console.error('Error loading list:', error);
      } finally {
        this.loading = false;
      }
    },

    onSearchChange() {
      this.list.page = 1;
      this.loadList();
    },

    applyFilters() {
      this.list.page = 1;
      this.loadList();
    },

    resetFilters() {
      this.searchQuery = '';
      this.filters = {
        fromHotspotId: null,
        toHotspotId: null,
        travelLocationType: null,
      };
      this.list.page = 1;
      this.loadList();
    },

    nextPage() {
      if (this.list.page < this.list.pages) {
        this.list.page++;
        this.loadList();
      }
    },

    previousPage() {
      if (this.list.page > 1) {
        this.list.page--;
        this.loadList();
      }
    },

    toggleSelectAll() {
      if (this.selectAll) {
        this.selectedIds = this.list.rows.map((r) => r.id);
      } else {
        this.selectedIds = [];
      }
    },

    openCreateModal() {
      this.editingId = null;
      this.form = {
        fromHotspotId: null,
        toHotspotId: null,
        travelLocationType: 1,
        haversineKm: 0,
        correctionFactor: 1.5,
        distanceKm: 0,
        speedKmph: 40,
        travelTime: '00:00:00',
        method: 'HAVERSINE',
      };
      this.showModal = true;
    },

    async editEntry(row) {
      try {
        const response = await fetch(`/api/v1/hotspot-distance-cache/${row.id}`);
        const data = await response.json();
        this.editingId = row.id;
        this.form = {
          ...data,
        };
        this.showModal = true;
      } catch (error) {
        console.error('Error loading entry:', error);
      }
    },

    async saveEntry() {
      try {
        const method = this.editingId ? 'PUT' : 'POST';
        const url = '/api/v1/hotspot-distance-cache';
        const body = this.editingId ? { id: this.editingId, ...this.form } : this.form;

        const response = await fetch(url, {
          method,
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(body),
        });

        if (response.ok) {
          this.closeModal();
          this.loadList();
          alert(this.editingId ? 'Entry updated successfully' : 'Entry created successfully');
        } else {
          alert('Error saving entry');
        }
      } catch (error) {
        console.error('Error saving entry:', error);
        alert('Error saving entry');
      }
    },

    closeModal() {
      this.showModal = false;
      this.editingId = null;
    },

    async deleteEntry(id) {
      if (!confirm('Delete this entry?')) return;

      try {
        const response = await fetch(`/api/v1/hotspot-distance-cache/${id}`, {
          method: 'DELETE',
        });

        if (response.ok) {
          this.loadList();
          alert('Entry deleted successfully');
        } else {
          alert('Error deleting entry');
        }
      } catch (error) {
        console.error('Error deleting entry:', error);
        alert('Error deleting entry');
      }
    },

    async bulkDelete() {
      if (!confirm(`Delete ${this.selectedIds.length} selected entries?`)) return;

      try {
        const response = await fetch('/api/v1/hotspot-distance-cache/bulk-delete', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ ids: this.selectedIds }),
        });

        if (response.ok) {
          this.loadList();
          alert(`${this.selectedIds.length} entries deleted successfully`);
        } else {
          alert('Error deleting entries');
        }
      } catch (error) {
        console.error('Error deleting entries:', error);
        alert('Error deleting entries');
      }
    },

    async exportToExcel() {
      try {
        const params = new URLSearchParams({
          search: this.searchQuery,
          ...Object.fromEntries(Object.entries(this.filters).filter(([, v]) => v !== null && v !== '')),
        });

        const response = await fetch(`/api/v1/hotspot-distance-cache/export/excel?${params}`);
        const data = await response.json();

        if (data.ok) {
          // Decode base64 and download
          const binaryString = atob(data.data);
          const bytes = new Uint8Array(binaryString.length);
          for (let i = 0; i < binaryString.length; i++) {
            bytes[i] = binaryString.charCodeAt(i);
          }

          const blob = new Blob([bytes], {
            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
          });
          const url = URL.createObjectURL(blob);
          const link = document.createElement('a');
          link.href = url;
          link.download = data.fileName;
          link.click();
          URL.revokeObjectURL(url);
        } else {
          alert('Error exporting to Excel');
        }
      } catch (error) {
        console.error('Error exporting:', error);
        alert('Error exporting to Excel');
      }
    },
  },
};
</script>

<style scoped>
.hotspot-distance-cache-container {
  padding: 20px;
  background: #fff;
}

.cache-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.page-title {
  font-size: 24px;
  font-weight: bold;
  margin: 0;
}

.header-actions {
  display: flex;
  gap: 10px;
}

.filters-section {
  background: #f9f9f9;
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 15px;
  margin-bottom: 20px;
}

.filter-row {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 15px;
}

.filter-group {
  display: flex;
  flex-direction: column;
}

.filter-group label {
  font-weight: bold;
  margin-bottom: 5px;
  font-size: 12px;
}

.form-control {
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 8px;
  font-size: 14px;
}

.table-section {
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 4px;
  overflow: auto;
}

.table-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 15px;
  border-bottom: 1px solid #ddd;
  background: #f5f5f5;
}

.table-info {
  font-size: 14px;
  display: flex;
  gap: 15px;
}

.record-count,
.selected-count {
  color: #666;
}

.data-table {
  width: 100%;
  border-collapse: collapse;
}

.data-table thead {
  background: #f0f0f0;
  font-weight: bold;
}

.data-table th {
  padding: 10px;
  text-align: left;
  border-bottom: 2px solid #ddd;
  font-size: 12px;
}

.data-table td {
  padding: 10px;
  border-bottom: 1px solid #eee;
  font-size: 14px;
}

.data-table tr.selected {
  background: #e3f2fd;
}

.data-table tbody tr:hover {
  background: #f9f9f9;
}

.hotspot-cell {
  white-space: nowrap;
}

.hotspot-cell small {
  display: block;
  color: #666;
  font-size: 12px;
}

.badge {
  padding: 4px 8px;
  border-radius: 3px;
  font-size: 12px;
  font-weight: bold;
}

.badge-info {
  background: #e3f2fd;
  color: #1976d2;
}

.badge-warning {
  background: #fff3e0;
  color: #f57c00;
}

.action-buttons {
  display: flex;
  gap: 5px;
}

.btn {
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 8px 12px;
  cursor: pointer;
  font-size: 14px;
  transition: all 0.2s;
}

.btn:hover {
  opacity: 0.8;
}

.btn-primary {
  background: #1976d2;
  color: white;
}

.btn-success {
  background: #388e3c;
  color: white;
}

.btn-info {
  background: #0288d1;
  color: white;
}

.btn-danger {
  background: #d32f2f;
  color: white;
}

.btn-secondary {
  background: #757575;
  color: white;
}

.btn-sm {
  padding: 4px 8px;
  font-size: 12px;
}

.btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 15px;
  padding: 15px;
  border-top: 1px solid #ddd;
}

.page-info {
  font-size: 14px;
  color: #666;
}

.loading-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 60px 20px;
  color: #666;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #f0f0f0;
  border-top-color: #1976d2;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
  margin-bottom: 15px;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.modal {
  display: flex;
  align-items: center;
  justify-content: center;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  z-index: 1000;
}

.modal-content {
  background: white;
  border-radius: 8px;
  max-width: 600px;
  width: 90%;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px;
  border-bottom: 1px solid #ddd;
}

.modal-header h3 {
  margin: 0;
  font-size: 18px;
}

.btn-close {
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
}

.modal-body {
  padding: 20px;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  font-weight: bold;
  margin-bottom: 5px;
  font-size: 14px;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  padding: 15px;
  border-top: 1px solid #ddd;
}

.text-right {
  text-align: right;
}

.text-center {
  text-align: center;
  color: #999;
  padding: 30px !important;
}
</style>
