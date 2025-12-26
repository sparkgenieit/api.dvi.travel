/**
 * HotspotDistanceCache.jsx - React Component
 * Location: src/components/HotspotDistanceCache.jsx
 * 
 * Features:
 * - List cache entries with pagination
 * - Search by from/to hotspot names
 * - Filter by hotspot ID and travel type
 * - Create new cache entry
 * - Edit existing cache entry
 * - Delete single or bulk entries
 * - Export to Excel
 * - Sidebar integration under hotspots section
 */

import React, { useState, useEffect } from 'react';
import './HotspotDistanceCache.css';

const HotspotDistanceCache = () => {
  // State for list view
  const [list, setList] = useState({ total: 0, page: 1, pages: 1, rows: [] });
  const [hotspots, setHotspots] = useState([]);
  const [travelTypes] = useState([
    { id: 1, name: 'Local' },
    { id: 2, name: 'Outstation' },
  ]);

  // State for filters
  const [searchQuery, setSearchQuery] = useState('');
  const [filters, setFilters] = useState({
    fromHotspotId: '',
    toHotspotId: '',
    travelLocationType: '',
  });
  const [selectedIds, setSelectedIds] = useState([]);
  const [selectAll, setSelectAll] = useState(false);

  // State for modal
  const [showModal, setShowModal] = useState(false);
  const [editingId, setEditingId] = useState(null);
  const [form, setForm] = useState({
    fromHotspotId: '',
    toHotspotId: '',
    travelLocationType: 1,
    haversineKm: 0,
    correctionFactor: 1.5,
    distanceKm: 0,
    speedKmph: 40,
    travelTime: '00:00:00',
    method: 'HAVERSINE',
  });

  // State for UI
  const [loading, setLoading] = useState(false);

  // Load form options
  useEffect(() => {
    loadFormOptions();
    loadList();
  }, []);

  const loadFormOptions = async () => {
    try {
      const response = await fetch('/api/v1/hotspot-distance-cache/form-options');
      const data = await response.json();
      setHotspots(data.hotspots);
    } catch (error) {
      console.error('Error loading form options:', error);
    }
  };

  const loadList = async () => {
    setLoading(true);
    try {
      const params = new URLSearchParams({
        page: list.page,
        size: 50,
        search: searchQuery,
        ...Object.fromEntries(Object.entries(filters).filter(([, v]) => v !== '' && v !== null)),
      });

      const response = await fetch(`/api/v1/hotspot-distance-cache?${params}`);
      const data = await response.json();
      setList(data);
      setSelectedIds([]);
      setSelectAll(false);
    } catch (error) {
      console.error('Error loading list:', error);
    } finally {
      setLoading(false);
    }
  };

  const onSearchChange = (value) => {
    setSearchQuery(value);
    setList({ ...list, page: 1 });
  };

  const applyFilters = () => {
    setList({ ...list, page: 1 });
  };

  const resetFilters = () => {
    setSearchQuery('');
    setFilters({
      fromHotspotId: '',
      toHotspotId: '',
      travelLocationType: '',
    });
    setList({ ...list, page: 1 });
  };

  const nextPage = () => {
    if (list.page < list.pages) {
      setList({ ...list, page: list.page + 1 });
    }
  };

  const previousPage = () => {
    if (list.page > 1) {
      setList({ ...list, page: list.page - 1 });
    }
  };

  const toggleSelectAll = (checked) => {
    setSelectAll(checked);
    if (checked) {
      setSelectedIds(list.rows.map((r) => r.id));
    } else {
      setSelectedIds([]);
    }
  };

  const toggleRowSelection = (id) => {
    setSelectedIds((prev) =>
      prev.includes(id) ? prev.filter((x) => x !== id) : [...prev, id]
    );
  };

  const openCreateModal = () => {
    setEditingId(null);
    setForm({
      fromHotspotId: '',
      toHotspotId: '',
      travelLocationType: 1,
      haversineKm: 0,
      correctionFactor: 1.5,
      distanceKm: 0,
      speedKmph: 40,
      travelTime: '00:00:00',
      method: 'HAVERSINE',
    });
    setShowModal(true);
  };

  const editEntry = async (row) => {
    try {
      const response = await fetch(`/api/v1/hotspot-distance-cache/${row.id}`);
      const data = await response.json();
      setEditingId(row.id);
      setForm(data);
      setShowModal(true);
    } catch (error) {
      console.error('Error loading entry:', error);
    }
  };

  const saveEntry = async (e) => {
    e.preventDefault();
    try {
      const method = editingId ? 'PUT' : 'POST';
      const url = '/api/v1/hotspot-distance-cache';
      const body = editingId ? { id: editingId, ...form } : form;

      const response = await fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(body),
      });

      if (response.ok) {
        setShowModal(false);
        loadList();
        alert(editingId ? 'Entry updated successfully' : 'Entry created successfully');
      } else {
        alert('Error saving entry');
      }
    } catch (error) {
      console.error('Error saving entry:', error);
      alert('Error saving entry');
    }
  };

  const deleteEntry = async (id) => {
    if (!window.confirm('Delete this entry?')) return;

    try {
      const response = await fetch(`/api/v1/hotspot-distance-cache/${id}`, {
        method: 'DELETE',
      });

      if (response.ok) {
        loadList();
        alert('Entry deleted successfully');
      } else {
        alert('Error deleting entry');
      }
    } catch (error) {
      console.error('Error deleting entry:', error);
      alert('Error deleting entry');
    }
  };

  const bulkDelete = async () => {
    if (!window.confirm(`Delete ${selectedIds.length} selected entries?`)) return;

    try {
      const response = await fetch('/api/v1/hotspot-distance-cache/bulk-delete', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ids: selectedIds }),
      });

      if (response.ok) {
        loadList();
        alert(`${selectedIds.length} entries deleted successfully`);
      } else {
        alert('Error deleting entries');
      }
    } catch (error) {
      console.error('Error deleting entries:', error);
      alert('Error deleting entries');
    }
  };

  const exportToExcel = async () => {
    try {
      const params = new URLSearchParams({
        search: searchQuery,
        ...Object.fromEntries(Object.entries(filters).filter(([, v]) => v !== '' && v !== null)),
      });

      const response = await fetch(`/api/v1/hotspot-distance-cache/export/excel?${params}`);
      const data = await response.json();

      if (data.ok) {
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
  };

  // Use this effect to load list whenever page or filters change
  useEffect(() => {
    loadList();
  }, [list.page, searchQuery, filters]);

  return (
    <div className="hotspot-distance-cache-container">
      {/* Header */}
      <div className="cache-header">
        <h2 className="page-title">Hotspot Distance Cache</h2>
        <div className="header-actions">
          <button className="btn btn-primary" onClick={openCreateModal}>
            <i className="icon-plus"></i> Add Cache Entry
          </button>
          <button className="btn btn-success" onClick={exportToExcel} disabled={loading}>
            <i className="icon-download"></i> Export Excel
          </button>
        </div>
      </div>

      {/* Search & Filters */}
      <div className="filters-section">
        <div className="filter-row">
          <div className="filter-group">
            <label>Search (Name)</label>
            <input
              type="text"
              placeholder="Search by hotspot name..."
              className="form-control"
              value={searchQuery}
              onChange={(e) => onSearchChange(e.target.value)}
            />
          </div>

          <div className="filter-group">
            <label>From Hotspot</label>
            <select
              className="form-control"
              value={filters.fromHotspotId}
              onChange={(e) => {
                setFilters({ ...filters, fromHotspotId: e.target.value });
                applyFilters();
              }}
            >
              <option value="">All</option>
              {hotspots.map((hs) => (
                <option key={hs.id} value={hs.id}>
                  {hs.name}
                </option>
              ))}
            </select>
          </div>

          <div className="filter-group">
            <label>To Hotspot</label>
            <select
              className="form-control"
              value={filters.toHotspotId}
              onChange={(e) => {
                setFilters({ ...filters, toHotspotId: e.target.value });
                applyFilters();
              }}
            >
              <option value="">All</option>
              {hotspots.map((hs) => (
                <option key={hs.id} value={hs.id}>
                  {hs.name}
                </option>
              ))}
            </select>
          </div>

          <div className="filter-group">
            <label>Travel Type</label>
            <select
              className="form-control"
              value={filters.travelLocationType}
              onChange={(e) => {
                setFilters({ ...filters, travelLocationType: e.target.value });
                applyFilters();
              }}
            >
              <option value="">All Types</option>
              <option value="1">Local</option>
              <option value="2">Outstation</option>
            </select>
          </div>

          <div className="filter-group">
            <label>&nbsp;</label>
            <button className="btn btn-secondary" onClick={resetFilters}>
              Clear Filters
            </button>
          </div>
        </div>
      </div>

      {/* Data Table */}
      {!loading && (
        <div className="table-section">
          <div className="table-header">
            <div className="table-info">
              <span className="record-count">{list.total} entries</span>
              {selectedIds.length > 0 && (
                <span className="selected-count">{selectedIds.length} selected</span>
              )}
            </div>
            {selectedIds.length > 0 && (
              <div className="table-actions">
                <button className="btn btn-danger btn-sm" onClick={bulkDelete}>
                  Delete Selected ({selectedIds.length})
                </button>
              </div>
            )}
          </div>

          <table className="data-table">
            <thead>
              <tr>
                <th style={{ width: '40px' }}>
                  <input
                    type="checkbox"
                    checked={selectAll}
                    onChange={(e) => toggleSelectAll(e.target.checked)}
                  />
                </th>
                <th style={{ width: '60px' }}>ID</th>
                <th>From Hotspot</th>
                <th>To Hotspot</th>
                <th style={{ width: '80px' }}>Type</th>
                <th style={{ width: '100px' }}>Distance</th>
                <th style={{ width: '80px' }}>Speed</th>
                <th style={{ width: '90px' }}>Travel Time</th>
                <th style={{ width: '100px' }}>Actions</th>
              </tr>
            </thead>
            <tbody>
              {list.rows.length === 0 ? (
                <tr>
                  <td colSpan="9" style={{ textAlign: 'center' }}>
                    No cache entries found
                  </td>
                </tr>
              ) : (
                list.rows.map((row) => (
                  <tr
                    key={row.id}
                    className={selectedIds.includes(row.id) ? 'selected' : ''}
                  >
                    <td>
                      <input
                        type="checkbox"
                        checked={selectedIds.includes(row.id)}
                        onChange={() => toggleRowSelection(row.id)}
                      />
                    </td>
                    <td>{row.id}</td>
                    <td>
                      <div className="hotspot-cell">
                        <strong>{row.fromHotspotId}</strong>
                        <br />
                        <small>{row.fromHotspotName}</small>
                      </div>
                    </td>
                    <td>
                      <div className="hotspot-cell">
                        <strong>{row.toHotspotId}</strong>
                        <br />
                        <small>{row.toHotspotName}</small>
                      </div>
                    </td>
                    <td>
                      <span
                        className={
                          row.travelLocationType === 1 ? 'badge badge-info' : 'badge badge-warning'
                        }
                      >
                        {row.travelLocationType === 1 ? 'Local' : 'Outstation'}
                      </span>
                    </td>
                    <td style={{ textAlign: 'right' }}>
                      {Number(row.distanceKm).toFixed(2)} km
                    </td>
                    <td style={{ textAlign: 'right' }}>
                      {Number(row.speedKmph).toFixed(0)} km/h
                    </td>
                    <td>{row.travelTime}</td>
                    <td>
                      <div className="action-buttons">
                        <button
                          className="btn btn-sm btn-info"
                          onClick={() => editEntry(row)}
                          title="Edit"
                        >
                          Edit
                        </button>
                        <button
                          className="btn btn-sm btn-danger"
                          onClick={() => deleteEntry(row.id)}
                          title="Delete"
                        >
                          Delete
                        </button>
                      </div>
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>

          {/* Pagination */}
          <div className="pagination">
            <button
              className="btn btn-sm"
              onClick={previousPage}
              disabled={list.page <= 1}
            >
              Previous
            </button>
            <span className="page-info">
              Page {list.page} of {list.pages}
            </span>
            <button
              className="btn btn-sm"
              onClick={nextPage}
              disabled={list.page >= list.pages}
            >
              Next
            </button>
          </div>
        </div>
      )}

      {/* Loading State */}
      {loading && (
        <div className="loading-state">
          <div className="spinner"></div>
          <p>Loading cache entries...</p>
        </div>
      )}

      {/* Create/Edit Modal */}
      {showModal && (
        <div className="modal" onClick={() => setShowModal(false)}>
          <div className="modal-content" onClick={(e) => e.stopPropagation()}>
            <div className="modal-header">
              <h3>{editingId ? 'Edit' : 'Create'} Cache Entry</h3>
              <button className="btn-close" onClick={() => setShowModal(false)}>
                ×
              </button>
            </div>

            <div className="modal-body">
              <form onSubmit={saveEntry}>
                <div className="form-group">
                  <label>From Hotspot *</label>
                  <select
                    className="form-control"
                    value={form.fromHotspotId}
                    onChange={(e) =>
                      setForm({ ...form, fromHotspotId: Number(e.target.value) })
                    }
                    required
                  >
                    <option value="">Select hotspot</option>
                    {hotspots.map((hs) => (
                      <option key={hs.id} value={hs.id}>
                        {hs.name} (ID: {hs.id})
                      </option>
                    ))}
                  </select>
                </div>

                <div className="form-group">
                  <label>To Hotspot *</label>
                  <select
                    className="form-control"
                    value={form.toHotspotId}
                    onChange={(e) =>
                      setForm({ ...form, toHotspotId: Number(e.target.value) })
                    }
                    required
                  >
                    <option value="">Select hotspot</option>
                    {hotspots.map((hs) => (
                      <option key={hs.id} value={hs.id}>
                        {hs.name} (ID: {hs.id})
                      </option>
                    ))}
                  </select>
                </div>

                <div className="form-row">
                  <div className="form-group">
                    <label>Travel Type *</label>
                    <select
                      className="form-control"
                      value={form.travelLocationType}
                      onChange={(e) =>
                        setForm({ ...form, travelLocationType: Number(e.target.value) })
                      }
                      required
                    >
                      <option value="1">Local</option>
                      <option value="2">Outstation</option>
                    </select>
                  </div>

                  <div className="form-group">
                    <label>Speed (km/h) *</label>
                    <input
                      type="number"
                      className="form-control"
                      value={form.speedKmph}
                      onChange={(e) =>
                        setForm({ ...form, speedKmph: Number(e.target.value) })
                      }
                      required
                    />
                  </div>
                </div>

                <div className="form-row">
                  <div className="form-group">
                    <label>Haversine (km) *</label>
                    <input
                      type="number"
                      step="0.0001"
                      className="form-control"
                      value={form.haversineKm}
                      onChange={(e) =>
                        setForm({ ...form, haversineKm: Number(e.target.value) })
                      }
                      required
                    />
                  </div>

                  <div className="form-group">
                    <label>Correction Factor *</label>
                    <input
                      type="number"
                      step="0.001"
                      className="form-control"
                      value={form.correctionFactor}
                      onChange={(e) =>
                        setForm({ ...form, correctionFactor: Number(e.target.value) })
                      }
                      required
                    />
                  </div>
                </div>

                <div className="form-row">
                  <div className="form-group">
                    <label>Distance (km) *</label>
                    <input
                      type="number"
                      step="0.0001"
                      className="form-control"
                      value={form.distanceKm}
                      onChange={(e) =>
                        setForm({ ...form, distanceKm: Number(e.target.value) })
                      }
                      required
                    />
                  </div>

                  <div className="form-group">
                    <label>Travel Time (HH:MM:SS) *</label>
                    <input
                      type="text"
                      placeholder="00:30:00"
                      className="form-control"
                      value={form.travelTime}
                      onChange={(e) => setForm({ ...form, travelTime: e.target.value })}
                      required
                    />
                  </div>
                </div>

                <div className="form-group">
                  <label>Method</label>
                  <input
                    type="text"
                    className="form-control"
                    value={form.method}
                    onChange={(e) => setForm({ ...form, method: e.target.value })}
                  />
                </div>

                <div className="modal-footer">
                  <button
                    type="button"
                    className="btn btn-secondary"
                    onClick={() => setShowModal(false)}
                  >
                    Cancel
                  </button>
                  <button type="submit" className="btn btn-primary">
                    {editingId ? 'Update' : 'Create'}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default HotspotDistanceCache;
