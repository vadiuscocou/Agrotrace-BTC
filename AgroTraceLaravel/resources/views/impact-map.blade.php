@extends('layouts.app')
@section('title', 'Impact Map')

@section('content')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

<style>
    #impact-map {
        height: 70vh;
        width: 100%;
        border-radius: 1.5rem;
        z-index: 10;
    }
    .custom-popup .leaflet-popup-content-wrapper {
        border-radius: 1rem;
        padding: 0;
        overflow: hidden;
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    }
    .custom-popup .leaflet-popup-content {
        margin: 0;
        width: 250px !important;
    }
    .custom-popup .leaflet-popup-tip-container {
        display: none;
    }
</style>

<div class="bg-white border-b border-slate-200 px-8 py-10">
    <div class="max-w-7xl mx-auto">
        <div class="inline-flex items-center gap-2 px-3 py-1 bg-green-50 text-green-700 rounded-full text-[10px] font-black uppercase tracking-widest mb-3">
            <i class="fa-solid fa-earth-africa"></i> Traceabilité Globale
        </div>
        <h1 class="text-4xl font-black tracking-tight text-slate-900 mb-4">Impact Map</h1>
        <p class="text-slate-500 text-lg max-w-2xl font-medium">
            Découvrez en temps réel où vos investissements Bitcoin se transforment en projets agricoles concrets à travers l'Afrique. Transparence totale, de la blockchain à la graine.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-8 py-10">
    <div class="bg-white p-4 rounded-[2rem] shadow-sm border border-slate-100">
        <div id="impact-map"></div>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize the map, centered on West Africa roughly
        var map = L.map('impact-map').setView([10.0, -0.0], 5);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            className: 'map-tiles'
        }).addTo(map);

        // Custom icon for projects
        var tractorIcon = L.divIcon({
            html: '<div class="h-10 w-10 bg-[#063b27] text-white rounded-full flex items-center justify-center shadow-lg border-2 border-white"><i class="fa-solid fa-tractor"></i></div>',
            className: 'custom-div-icon',
            iconSize: [40, 40],
            iconAnchor: [20, 20],
            popupAnchor: [0, -20]
        });

        // Pass PHP data to JS
        var projects = @json($projects);

        // Add markers for each project
        projects.forEach(function(project) {
            if (project.latitude && project.longitude) {
                var statusBadge = project.status === 'active' 
                    ? '<span class="px-2 py-1 bg-green-100 text-green-700 text-[10px] font-black uppercase rounded-md mb-2 inline-block">Actif</span>'
                    : '<span class="px-2 py-1 bg-orange-100 text-orange-700 text-[10px] font-black uppercase rounded-md mb-2 inline-block">En attente</span>';

                var formatter = new Intl.NumberFormat('fr-FR');
                var budget = formatter.format(project.target_amount_fcfa) + ' FCFA';

                var popupContent = `
                    <div class="p-0">
                        <div class="h-24 bg-slate-200 relative overflow-hidden">
                            <img src="${project.image || 'https://images.unsplash.com/photo-1592841200221-a6898f307baa?auto=format&fit=crop&q=80&w=400'}" class="w-full h-full object-cover" alt="Project Image">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        </div>
                        <div class="p-4 bg-white">
                            ${statusBadge}
                            <h3 class="font-bold text-slate-900 text-sm mb-1 leading-tight">${project.title}</h3>
                            <p class="text-xs text-slate-500 mb-3"><i class="fa-solid fa-location-dot mr-1"></i> ${project.region}</p>
                            
                            <div class="flex justify-between items-end border-t border-slate-100 pt-3">
                                <div>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Objectif</p>
                                    <p class="font-black text-[#063b27] text-sm">${budget}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                var marker = L.marker([project.latitude, project.longitude], {icon: tractorIcon}).addTo(map);
                marker.bindPopup(popupContent, {className: 'custom-popup'});
            }
        });
    });
</script>
@endsection
