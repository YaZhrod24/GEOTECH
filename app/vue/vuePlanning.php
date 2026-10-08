<?php
require_once Racine . '/../app/vue/layout/entete.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h2 fw-bold mb-1">Planning</h1>
            <p class="text-muted mb-0">Visualisez les interventions planifiées, jour par jour.</p>
        </div>
        <span class="badge text-bg-secondary">En attente du module interventions</span>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-3 p-md-4">
            <div id="planning-calendar"></div>
            <p class="text-muted text-center mb-0 mt-3">
                Aucune intervention n'est encore reliée au planning.
            </p>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarElement = document.getElementById('planning-calendar');
        const calendar = new FullCalendar.Calendar(calendarElement, {
            locale: 'fr',
            initialView: 'timeGridDay',
            firstDay: 1,
            nowIndicator: true,
            allDaySlot: true,
            height: 'auto',
            expandRows: true,
            slotMinTime: '06:00:00',
            slotMaxTime: '22:00:00',
            eventDisplay: 'block',
            buttonText: {
                today: "Aujourd'hui"
            },
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            events: []
        });

        calendar.render();
    });
</script>

<?php
require_once Racine . '/../app/vue/layout/pied.php';
?>
