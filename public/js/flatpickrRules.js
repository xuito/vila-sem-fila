$('.flatpickr_date').flatpickr({
    locale: 'pt',
    dateFormat: 'd/m/Y',
    disableMobile: true
});

$('.flatpickr_datetime').flatpickr({
    locale: 'pt',
    dateFormat: 'd/m/Y H:i',
    disableMobile: true
});

$('.flatpickr_time').flatpickr({
    enableTime: true,
    noCalendar: true,
    dateFormat: 'H:i',
    time_24hr: true,
    disableMobile: true
});
