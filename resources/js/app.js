
//NAVBAR

function toggleDropdown(dropdownId) {
    const dropdown = document.getElementById(dropdownId);
    const arrowId = dropdownId.replace('Dropdown', 'Arrow');
    const arrow = document.getElementById(arrowId);
    
    // Close all other dropdowns first
    const allDropdowns = document.querySelectorAll('[id$="Dropdown"]');
    const allArrows = document.querySelectorAll('[id$="Arrow"]');
    
    allDropdowns.forEach(d => {
        if (d.id !== dropdownId && !d.classList.contains('hidden')) {
            d.classList.add('hidden');
        }
    });
    
    allArrows.forEach(a => {
        if (a.id !== arrowId) {
            a.style.transform = 'rotate(0deg)';
        }
    });
    
    // Toggle current dropdown
    if (dropdown.classList.contains('hidden')) {
        dropdown.classList.remove('hidden');
        arrow.style.transform = 'rotate(180deg)';
    } else {
        dropdown.classList.add('hidden');
        arrow.style.transform = 'rotate(0deg)';
    }
}

// Auto-open dropdown if current route is one of the sub-pages
document.addEventListener('DOMContentLoaded', function() {
    const currentRoute = window.location.pathname;
    
    // Define route mappings for each section
    const routeMappings = {
        'dashboardDropdown': ['dashboard', 'data-dosen', 'data-tpa', 'dashboard-dosen', 'dashboard-tpa', 'dashboard-kompetisi', 'kompetisi'],
        'dosenDropdown': ['manajemen-dosen', 'kelola-dosen'],
        'tpaDropdown': ['manajemen-tpa'],
        'rekrutasiDropdown': ['rekrutasi-dosen'],
        'mahasiswaDropdown': ['manajemen-mahasiswa'],
        'masterDataDropdown': ['master-data'],
        'pengaturanDropdown': ['pengaturan']
    };
    
    // Check each dropdown and auto-open if current route matches
    Object.keys(routeMappings).forEach(dropdownId => {
        const routes = routeMappings[dropdownId];
        const shouldOpen = routes.some(route => currentRoute.includes(route));
        
        if (shouldOpen) {
            const dropdown = document.getElementById(dropdownId);
            const arrowId = dropdownId.replace('Dropdown', 'Arrow');
            const arrow = document.getElementById(arrowId);
            
            if (dropdown && arrow) {
                dropdown.classList.remove('hidden');
                arrow.style.transform = 'rotate(180deg)';
            }
        }
    });
});

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const nav = event.target.closest('nav');
    if (!nav) return;
    
    const isDropdownButton = event.target.closest('button[onclick*="toggleDropdown"]');
    if (!isDropdownButton) {
        const allDropdowns = document.querySelectorAll('[id$="Dropdown"]');
        const allArrows = document.querySelectorAll('[id$="Arrow"]');
        
        allDropdowns.forEach(d => d.classList.add('hidden'));
        allArrows.forEach(a => a.style.transform = 'rotate(0deg)');
    }
});