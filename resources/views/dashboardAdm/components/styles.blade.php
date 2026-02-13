<style>
  /* ==================== TAILWIND UTILITIES ==================== */
  
  /* Grid Classes */
  .grid {
    display: grid;
  }
  .grid-cols-1 {
    grid-template-columns: repeat(1, minmax(0, 1fr));
  }
  @media (min-width: 768px) {
    .md\:grid-cols-2 {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    .grid.md\:grid-cols-2 {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
  }
  @media (min-width: 1024px) {
    .lg\:grid-cols-4 {
      grid-template-columns: repeat(4, minmax(0, 1fr));
    }
    .grid.lg\:grid-cols-4 {
      grid-template-columns: repeat(4, minmax(0, 1fr));
    }
  }
  
  /* Animations */
  .animate-in {
    animation: slideIn 0.3s ease-in;
  }
  @keyframes slideIn {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* ==================== COMPONENT STYLES ==================== */
  
  .stat-card {
    border-radius: 1rem;
    box-shadow: 0 4px 24px rgba(80, 80, 180, 0.08);
    transition: box-shadow 0.2s, transform 0.2s;
  }
  .stat-card:hover {
    box-shadow: 0 8px 32px rgba(80, 80, 180, 0.16);
    transform: translateY(-4px) scale(1.04);
  }
  
  .table-hover tbody tr:hover {
    background-color: #f3f4f6 !important;
    transition: background 0.2s;
  }
  
  .btn-outline-danger:hover {
    background: #fee2e2;
    color: #b91c1c;
    border-color: #f87171;
  }
  .btn-light:hover {
    background: #e0e7ff;
    color: #3730a3;
  }

  /* ==================== GRADIENT BACKGROUNDS ==================== */
  
  .bg-gradient-to-br.from-blue-400.to-blue-600 {
    background: linear-gradient(135deg, #60a5fa 0%, #2563eb 100%);
  }
  
  .bg-gradient-to-br.from-green-400.to-green-600 {
    background: linear-gradient(135deg, #4ade80 0%, #16a34a 100%);
  }
  
  .bg-gradient-to-br.from-yellow-400.to-yellow-600 {
    background: linear-gradient(135deg, #facc15 0%, #ca8a04 100%);
  }
  
  .bg-gradient-to-br.from-red-400.to-red-600 {
    background: linear-gradient(135deg, #f87171 0%, #dc2626 100%);
  }

  .bg-gradient-to-br.from-blue-50.to-blue-100 {
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
  }

  .bg-gradient-to-br.from-green-50.to-green-100 {
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
  }

  .bg-gradient-to-br.from-orange-50.to-orange-100 {
    background: linear-gradient(135deg, #fff7ed 0%, #fed7aa 100%);
  }

  .bg-gradient-to-br.from-red-50.to-red-100 {
    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
  }
  
  .bg-gradient-to-r.from-indigo-600.to-indigo-800 {
    background: linear-gradient(90deg, #4f46e5 0%, #3730a3 100%);
  }
  
  .bg-gradient-to-r.from-indigo-100.to-indigo-50 {
    background: linear-gradient(90deg, #e0e7ff 0%, #f5f3ff 100%);
  }

  .bg-gradient-to-r.from-emerald-500.to-emerald-700 {
    background: linear-gradient(90deg, #10b981 0%, #047857 100%);
  }

  .bg-gradient-to-r.from-emerald-600.to-teal-600 {
    background: linear-gradient(90deg, #059669 0%, #0d9488 100%);
  }

  .bg-gradient-to-r.from-orange-500.to-orange-700 {
    background: linear-gradient(90deg, #f97316 0%, #ea580c 100%);
  }

  .bg-gradient-to-r.from-pink-500.to-rose-500 {
    background: linear-gradient(90deg, #ec4899 0%, #f43f5e 100%);
  }

  .bg-gradient-to-r.from-indigo-600.to-purple-600 {
    background: linear-gradient(90deg, #4f46e5 0%, #9333ea 100%);
  }

  .bg-gradient-to-r.from-slate-700.to-slate-900 {
    background: linear-gradient(90deg, #374151 0%, #111827 100%);
  }

  .bg-gradient-to-r.from-blue-600.to-blue-800 {
    background: linear-gradient(90deg, #2563eb 0%, #1e40af 100%);
  }

  .bg-gradient-to-r.from-purple-600.to-pink-600 {
    background: linear-gradient(90deg, #9333ea 0%, #ec4899 100%);
  }

  .bg-gradient-to-r.from-green-600.to-emerald-600 {
    background: linear-gradient(90deg, #16a34a 0%, #059669 100%);
  }

  .bg-gradient-to-r.from-orange-600.to-red-600 {
    background: linear-gradient(90deg, #ea580c 0%, #dc2626 100%);
  }

  .bg-gradient-to-br.from-purple-50.to-purple-100 {
    background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%);
  }

  .bg-gradient-to-br.from-green-50.to-emerald-100 {
    background: linear-gradient(135deg, #f0fdf4 0%, #d1fae5 100%);
  }

  .bg-gradient-to-br.from-blue-50.to-cyan-100 {
    background: linear-gradient(135deg, #eff6ff 0%, #cffafe 100%);
  }

  .bg-gradient-to-br.from-orange-50.to-red-100 {
    background: linear-gradient(135deg, #fff7ed 0%, #fee2e2 100%);
  }

  /* ==================== BACKGROUND COLORS ==================== */
  
  .bg-slate-50 {
    background-color: #f8fafc;
  }
  
  .bg-blue-100 {
    background-color: #dbeafe;
  }

  .bg-blue-50 {
    background-color: #eff6ff;
  }

  .bg-green-50 {
    background-color: #f0fdf4;
  }

  .bg-green-100 {
    background-color: #dcfce7;
  }

  .bg-orange-50 {
    background-color: #fff7ed;
  }

  .bg-orange-100 {
    background-color: #fed7aa;
  }

  .bg-red-50 {
    background-color: #fef2f2;
  }

  .bg-red-100 {
    background-color: #fee2e2;
  }

  .bg-emerald-50 {
    background-color: #f0fdf4;
  }

  .bg-indigo-100 {
    background-color: #e0e7ff;
  }

  .bg-purple-50 {
    background-color: #faf5ff;
  }

  .bg-purple-100 {
    background-color: #f3e8ff;
  }

  .bg-pink-50 {
    background-color: #fdf2f8;
  }

  .bg-pink-100 {
    background-color: #fbecf8;
  }

  .text-white {
    color: white;
  }

  /* ==================== TEXT COLORS ==================== */
  
  .text-blue-100 {
    color: #dbeafe;
  }

  .text-blue-600 {
    color: #2563eb;
  }
  
  .text-green-100 {
    color: #dcfce7;
  }

  .text-green-600 {
    color: #16a34a;
  }
  
  .text-yellow-100 {
    color: #fef3c7;
  }
  
  .text-red-100 {
    color: #fee2e2;
  }

  .text-slate-500 {
    color: #64748b;
  }

  .text-indigo-100 {
    color: #e0e7ff;
  }

  .text-yellow-300 {
    color: #fcd34d;
  }

  .text-pink-100 {
    color: #fbecf8;
  }

  .text-pink-300 {
    color: #f472b6;
  }

  .text-pink-600 {
    color: #ec4899;
  }

  .text-purple-300 {
    color: #d8b4fe;
  }

  .text-purple-600 {
    color: #9333ea;
  }

  .text-orange-100 {
    color: #ffedd5;
  }

  .text-orange-600 {
    color: #ea580c;
  }

  /* ==================== FLEX & DISPLAY ==================== */

  .flex {
    display: flex;
  }

  .items-center {
    align-items: center;
  }

  .justify-between {
    justify-content: space-between;
  }

  .gap-2 {
    gap: 0.5rem;
  }

  .flex-wrap {
    flex-wrap: wrap;
  }

  .w-full {
    width: 100%;
  }

  .w-8 {
    width: 2rem;
  }

  .h-8 {
    height: 2rem;
  }

  .overflow-hidden {
    overflow: hidden;
  }

  .overflow-x-auto {
    overflow-x: auto;
  }

  /* ==================== TRANSITIONS ==================== */

  .transition {
    transition-property: color, background-color, border-color, box-shadow, transform;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
  }

  .transition-colors {
    transition-property: background-color, border-color, color, fill, stroke;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
  }

  .duration-200 {
    transition-duration: 200ms;
  }

  /* ==================== UTILITIES ==================== */

  .btn-secondary {
    background-color: #6b7280 !important;
    color: #ffffff !important;
    border-color: #6b7280 !important;
  }

  .btn-secondary:hover {
    background-color: #4b5563 !important;
    border-color: #4b5563 !important;
  }

  .btn-outline-warning {
    color: #f59e0b;
    border-color: #f59e0b;
  }

  .btn-outline-warning:hover {
    color: #fff;
    background-color: #f59e0b;
    border-color: #f59e0b;
  }

  .table-hover tbody tr:hover {
    background-color: #f3f4f6 !important;
  }

  .d-flex {
    display: flex;
  }

  .gap-2 {
    gap: 0.5rem;
  }

  .mr-1 {
    margin-right: 0.25rem;
  }

  .mr-2 {
    margin-right: 0.5rem;
  }

  .mr-3 {
    margin-right: 0.75rem;
  }

  .ml-4 {
    margin-left: 1rem;
  }

  .animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
  }

  @keyframes pulse {
    0%, 100% {
      opacity: 1;
    }
    50% {
      opacity: 0.5;
    }
  }

  .text-red-100 {
    color: #fee2e2;
    color: #fee2e2;
  }

  .text-red-600 {
    color: #dc2626;
  }

  .text-orange-600 {
    color: #ea580c;
  }
  
  .text-slate-600 {
    color: #475569;
  }

  .text-slate-500 {
    color: #64748b;
  }

  .text-slate-800 {
    color: #1e293b;
  }

  .text-slate-300 {
    color: #cbd5e1;
  }

  .text-slate-500 {
    color: #64748b;
  }

  .text-slate-800 {
    color: #1e293b;
  }

  .text-blue-300 {
    color: #93c5fd;
  }

  .text-blue-600 {
    color: #2563eb;
  }
  
  .text-green-100 {
    color: #dcfce7;
  }

  .text-green-600 {
    color: #16a34a;
  }
  
  .text-yellow-100 {
    color: #fef3c7;
  }
  
  .text-red-100 {
    color: #fee2e2;
  }

  /* ==================== SHADOW & BORDER ==================== */

  .shadow-xl {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  }

  .shadow-lg {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  }

  .shadow-md {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  }

  .rounded-xl {
    border-radius: 0.75rem;
  }

  .rounded-lg {
    border-radius: 0.5rem;
  }

  .border-0 {
    border: 0;
  }

  .border-2 {
    border-width: 2px;
  }

  .border-green-400 {
    border-color: #4ade80;
  }

  /* ==================== BADGE COLORS ==================== */
  
  .badge {
    display: inline-block;
    padding: 0.5rem 0.75rem;
    border-radius: 0.375rem;
    font-weight: 500;
    font-size: 0.875rem;
  }
  
  .badge.bg-yellow-400 {
    background-color: #facc15 !important;
    color: #78350f !important;
  }
  
  .badge.bg-blue-400 {
    background-color: #60a5fa !important;
    color: #172554 !important;
  }

  .badge.bg-secondary {
    background-color: #6b7280 !important;
    color: #ffffff !important;
  }

  .badge.bg-primary {
    background-color: #3b82f6 !important;
    color: #ffffff !important;
  }

  .badge.bg-success {
    background-color: #10b981 !important;
    color: #ffffff !important;
  }

  .badge.bg-danger {
    background-color: #ef4444 !important;
    color: #ffffff !important;
  }

  .badge.bg-warning {
    background-color: #f59e0b !important;
    color: #ffffff !important;
  }

  .badge.bg-info {
    background-color: #06b6d4 !important;
    color: #ffffff !important;
  }

  .badge.bg-light {
    background-color: #f3f4f6 !important;
    color: #1f2937 !important;
  }

  .badge.bg-dark {
    background-color: #1f2937 !important;
    color: #ffffff !important;
  }

  .text-yellow-900 {
    color: #78350f !important;
  }

  .text-blue-900 {
    color: #172554 !important;
  }

  /* ==================== SPACING ==================== */
  
  .gap-4 {
    gap: 1rem;
  }

  .mt-6 {
    margin-top: 1.5rem;
  }

  .mb-6 {
    margin-bottom: 1.5rem;
  }

  .mb-4 {
    margin-bottom: 1rem;
  }

  .mb-3 {
    margin-bottom: 0.75rem;
  }

  .mt-1 {
    margin-top: 0.25rem;
  }

  .mt-2 {
    margin-top: 0.5rem;
  }

  .mb-0 {
    margin-bottom: 0;
  }

  .mb-1 {
    margin-bottom: 0.25rem;
  }

  .p-0 {
    padding: 0;
  }

  .p-4 {
    padding: 1rem;
  }

  .p-6 {
    padding: 1.5rem;
  }

  .px-4 {
    padding-left: 1rem;
    padding-right: 1rem;
  }

  .py-3 {
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
  }

  .py-6 {
    padding-top: 1.5rem;
    padding-bottom: 1.5rem;
  }

  .py-8 {
    padding-top: 2rem;
    padding-bottom: 2rem;
  }

  .ml-auto {
    margin-left: auto;
  }

  .ml-2 {
    margin-left: 0.5rem;
  }

  .mr-1 {
    margin-right: 0.25rem;
  }

  .mr-2 {
    margin-right: 0.5rem;
  }

  .mr-3 {
    margin-right: 0.75rem;
  }

  /* ==================== FLEX UTILITIES ==================== */
  
  .flex {
    display: flex;
  }

  .items-center {
    align-items: center;
  }

  .justify-between {
    justify-content: space-between;
  }

  .gap-2 {
    gap: 0.5rem;
  }

  .flex-1 {
    flex: 1 1 0%;
  }

  /* ==================== BORDER & RADIUS ==================== */
  
  .border-0 {
    border: 0;
  }

  .border-2 {
    border-width: 2px;
  }

  .border-l-4 {
    border-left: 4px solid;
  }

  .border-t {
    border-top: 1px solid #e5e7eb;
  }

  .border-blue-200 {
    border-color: #bfdbfe;
  }

  .border-blue-500 {
    border-color: #3b82f6;
  }

  .border-green-200 {
    border-color: #bbf7d0;
  }

  .border-green-500 {
    border-color: #22c55e;
  }

  .border-orange-200 {
    border-color: #fed7aa;
  }

  .border-orange-500 {
    border-color: #f97316;
  }

  .border-red-200 {
    border-color: #fecaca;
  }

  .border-red-500 {
    border-color: #ef4444;
  }

  .border-emerald-500 {
    border-color: #10b981;
  }

  .border-purple-600 {
    border-color: #9333ea;
  }

  .border-pink-600 {
    border-color: #ec4899;
  }

  .border-purple-200 {
    border-color: #e9d5ff;
  }

  .border-pink-200 {
    border-color: #fbcfe8;
  }

  .rounded-xl {
    border-radius: 1rem;
  }

  .rounded-lg {
    border-radius: 0.5rem;
  }

  .rounded-r-lg {
    border-top-right-radius: 0.5rem;
    border-bottom-right-radius: 0.5rem;
  }

  .rounded-full {
    border-radius: 9999px;
  }

  .overflow-hidden {
    overflow: hidden;
  }

  .overflow-x-auto {
    overflow-x: auto;
  }

  /* ==================== SHADOW & ELEVATION ==================== */
  
  .shadow-xl {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
  }

  .shadow {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  }

  /* ==================== SIZING ==================== */
  
  .w-8 {
    width: 2rem;
  }

  .h-8 {
    height: 2rem;
  }

  .w-full {
    width: 100%;
  }

  .text-4xl {
    font-size: 2.25rem;
  }

  /* ==================== TYPOGRAPHY ==================== */
  
  .text-xs {
    font-size: 0.75rem;
  }

  .text-sm {
    font-size: 0.875rem;
  }

  .text-lg {
    font-size: 1.125rem;
  }

  .text-3xl {
    font-size: 1.875rem;
  }

  .text-5xl {
    font-size: 3rem;
  }

  .font-bold {
    font-weight: 700;
  }

  .font-semibold {
    font-weight: 600;
  }

  h5 {
    font-size: 1.25rem;
    font-weight: 600;
  }

  /* ==================== HOVER & TRANSITION STATES ==================== */
  
  .hover\:shadow-2xl:hover {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  }

  .hover\:shadow-lg:hover {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  }

  .hover\:shadow-md:hover {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  }

  .hover\:scale-105:hover {
    transform: scale(1.05);
  }

  .hover\:scale-110:hover {
    transform: scale(1.1);
  }

  .hover\:bg-indigo-100:hover {
    background-color: #e0e7ff;
  }

  .hover\:border-blue-500:hover {
    border-color: #3b82f6;
  }

  .hover\:border-green-500:hover {
    border-color: #22c55e;
  }

  .hover\:border-orange-500:hover {
    border-color: #f97316;
  }

  .hover\:border-red-500:hover {
    border-color: #ef4444;
  }

  .transition {
    transition-property: color, background-color, border-color, text-decoration-color, fill, stroke;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
  }

  .transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
  }

  .transition-colors {
    transition-property: color, background-color, border-color, text-decoration-color, fill, stroke;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
  }

  .transition-transform {
    transition-property: transform;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  }

  .duration-200 {
    transition-duration: 200ms;
  }

  .duration-300 {
    transition-duration: 300ms;
  }

  .transform {
    transform: translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y));
  }

  /* ==================== OPACITY ==================== */
  
  .opacity-20 {
    opacity: 0.2;
  }

  .opacity-50 {
    opacity: 0.5;
  }

  /* ==================== DISPLAY ==================== */
  
  .block {
    display: block;
  }

  .hidden {
    display: none;
  }

  .text-center {
    text-align: center;
  }

  .text-right {
    text-align: right;
  }

  /* ==================== GROUP HOVER ==================== */
  
  .group:hover .group-hover\:scale-110 {
    transform: scale(1.1);
  }

  /* ==================== CARD STYLES ==================== */
  
  .card {
    border-radius: 1rem;
    overflow: hidden;
    border: 0;
  }

  .card-header {
    border-radius: 1rem 1rem 0 0;
  }

  .card-body {
    border-radius: 0 0 1rem 1rem;
  }

  .card-title {
    margin-bottom: 0;
  }

  /* ==================== SPACE UTILITIES ==================== */
  
  .space-y-3 > * + * {
    margin-top: 0.75rem;
  }

  /* ==================== TABLE STYLES ==================== */

  table {
    border-collapse: collapse;
    width: 100%;
  }

  .table thead {
    background-color: #f8fafc;
  }

  .table tbody tr {
    transition: background-color 0.2s ease;
  }

  .table tbody tr:hover {
    background-color: #f1f5f9 !important;
  }

  .table td, .table th {
    vertical-align: middle;
  }

  .table-hover tbody tr:nth-child(odd) {
    background-color: #ffffff;
  }

  .table-hover tbody tr:nth-child(even) {
    background-color: #f8fafc;
  }

  /* ==================== STATS DISPLAY ==================== */

  .stat-display {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem;
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border-radius: 0.5rem;
    border-left: 4px solid #0284c7;
  }

  .stat-number {
    font-size: 1.875rem;
    font-weight: bold;
    color: #0c4a6e;
  }

  .stat-label {
    font-size: 0.875rem;
    color: #475569;
    font-weight: 500;
  }
</style>
