<?php
require_once Racine . '/../app/vue/layout/entete.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h2 fw-bold mb-1">Planning</h1>
            <p class="text-muted mb-0">Visualisez les interventions planifiées, jour par jour.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-3 p-md-4">
            <div id="planning-calendar"></div>
            <p class="text-muted text-center mb-0 mt-3">
                Cliquez sur une intervention pour voir ses informations.
            </p>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js"></script>
<style>
    #planning-calendar {
        --fc-border-color: #e9ecef;
        --fc-today-bg-color: rgba(0, 191, 99, 0.08);
        --fc-now-indicator-color: #dc3545;
    }

    #planning-calendar .fc {
        font-family: inherit;
    }

    #planning-calendar .fc-toolbar-title {
        color: #212529;
        font-size: 1.35rem;
        font-weight: 700;
        text-transform: capitalize;
    }

    #planning-calendar .fc-button {
        border: 0;
        border-radius: 0.5rem;
        background-color: #00bf63;
        box-shadow: none;
        font-weight: 600;
    }

    #planning-calendar .fc-button:hover,
    #planning-calendar .fc-button:focus,
    #planning-calendar .fc-button-active {
        background-color: #009950;
        box-shadow: none;
    }

    #planning-calendar .fc-timegrid-slot {
        height: 3.25rem;
    }

    #planning-calendar .fc-timegrid-axis-cushion,
    #planning-calendar .fc-col-header-cell-cushion {
        color: #6c757d;
        font-weight: 600;
        text-decoration: none;
    }

    #planning-calendar .fc-event {
        border: 0;
        border-radius: 0.5rem;
        box-shadow: 0 0.2rem 0.45rem rgba(33, 37, 41, 0.15);
        cursor: pointer;
        margin: 2px 4px;
        padding: 0.35rem 0.45rem;
    }

    #planning-calendar .fc-event:hover {
        filter: brightness(0.94);
    }

    #planning-calendar .fc-event-main {
        overflow: hidden;
    }

    #planning-calendar .fc-event-main .small {
        line-height: 1.25;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
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
            slotEventOverlap: false,
            eventMaxStack: 4,
            eventMinHeight: 52,
            slotMinTime: '06:00:00',
            slotMaxTime: '22:00:00',
            eventDisplay: 'block',
            eventOrder: 'start,-duration,title',
            buttonText: {
                today: "Aujourd'hui"
            },
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            events: {
                url: '/planning/events',
                failure: function () {
                    calendarElement.insertAdjacentHTML(
                        'beforebegin',
                        '<div class="alert alert-danger">Impossible de charger les interventions.</div>'
                    );
                }
            },
            eventClick: function (info) {
                info.jsEvent.preventDefault();
                window.location.href = '/intervention/' + info.event.id;
            },
            eventContent: function (info) {
                const title = document.createElement('div');
                title.className = 'fw-semibold';
                title.textContent = info.event.title;

                const client = document.createElement('div');
                client.className = 'small';
                client.textContent = info.event.extendedProps.client || '';

                const technicien = document.createElement('div');
                technicien.className = 'small';
                technicien.textContent = 'Technicien : ' + (
                    info.event.extendedProps.technicien || 'Non assigné'
                );

                return { domNodes: [title, client, technicien] };
            },
            eventDidMount: function (info) {
                info.el.title = [
                    info.event.title,
                    info.event.extendedProps.client,
                    'Technicien : ' + (info.event.extendedProps.technicien || 'Non assigné')
                ].join('\n');
            }
        });

        calendar.render();
    });
</script>

<?php
require_once Racine . '/../app/vue/layout/pied.php';
?>