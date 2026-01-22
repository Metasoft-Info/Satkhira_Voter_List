import './bootstrap';

// Search functionality with cascading filters
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');
    const upazillaSelect = document.getElementById('upazilla-select');
    const pouroshovaUnionSelect = document.getElementById('pouroshova-union-select');
    const wardSelect = document.getElementById('ward-select');
    const resultsDiv = document.getElementById('results');

    // Sample data for cascading dropdowns (would be loaded from backend in real app)
    const locationData = {
        'satkhira-sadar': {
            pouroshovas: ['Satkhira Pouroshova'],
            unions: ['Bhomra', 'Brahmangaon', 'Dhulihar', 'Ghona', 'Jhaudanga', 'Kumira', 'Labsha', 'Munshiganj', 'Noapara', 'Ratanpur']
        },
        'assasuni': {
            pouroshovas: ['Assasuni Pouroshova'],
            unions: ['Assasuni', 'Baradal', 'Budhhata', 'Durgapur', 'Kadakati', 'Kharibaria', 'Kharnia', 'Pratapnagar', 'Sobhnali']
        },
        'debhata': {
            pouroshovas: ['Debhata Pouroshova'],
            unions: ['Chandanpur', 'Dauki', 'Debhata', 'Kushadanga', 'Noapara', 'Padmapukur', 'Sakhipur', 'Shibpur']
        },
        'kaliganj': {
            pouroshovas: ['Kaliganj Pouroshova'],
            unions: ['Balipara', 'Chhoygharia', 'Jalalabad', 'Kalinagar', 'Mautala', 'Nalta Mubaroknagar', 'Ramanathpur', 'Trimohini']
        },
        'shymnagar': {
            pouroshovas: ['Shymnagar Pouroshova'],
            unions: ['Atulia', 'Gabura', 'Ishwaripur', 'Kuskhali', 'Munshiganj', 'Padmapukur', 'Ramjanpur']
        },
        'tala': {
            pouroshovas: ['Tala Pouroshova'],
            unions: ['Dakshin Tala', 'Fingri', 'Joynagar', 'Khalia', 'Khalishpur', 'Magura', 'Nagarghata', 'Tala']
        }
    };

    // Handle Upazilla change
    upazillaSelect.addEventListener('change', function() {
        const selectedUpazilla = this.value;
        pouroshovaUnionSelect.innerHTML = '<option value="">Select Pouroshova/Union</option>';
        wardSelect.innerHTML = '<option value="">Select Ward</option>';
        wardSelect.disabled = true;

        if (selectedUpazilla && locationData[selectedUpazilla]) {
            const data = locationData[selectedUpazilla];

            // Add Pouroshovas
            data.pouroshovas.forEach(pouroshova => {
                const option = document.createElement('option');
                option.value = pouroshova.toLowerCase().replace(/\s+/g, '-');
                option.textContent = pouroshova;
                pouroshovaUnionSelect.appendChild(option);
            });

            // Add Unions
            data.unions.forEach(union => {
                const option = document.createElement('option');
                option.value = union.toLowerCase().replace(/\s+/g, '-');
                option.textContent = union + ' Union';
                pouroshovaUnionSelect.appendChild(option);
            });

            pouroshovaUnionSelect.disabled = false;
        } else {
            pouroshovaUnionSelect.disabled = true;
        }
    });

    // Handle Pouroshova/Union change
    pouroshovaUnionSelect.addEventListener('change', function() {
        const selectedPouroshovaUnion = this.value;
        wardSelect.innerHTML = '<option value="">Select Ward</option>';

        if (selectedPouroshovaUnion) {
            // Sample wards (1-9 for Pouroshovas, 1-9 for Unions)
            for (let i = 1; i <= 9; i++) {
                const option = document.createElement('option');
                option.value = `ward-${i}`;
                option.textContent = `Ward ${i}`;
                wardSelect.appendChild(option);
            }
            wardSelect.disabled = false;
        } else {
            wardSelect.disabled = true;
        }
    });

    function performSearch() {
        const query = searchInput.value.trim();
        const upazilla = upazillaSelect.options[upazillaSelect.selectedIndex].text;
        const pouroshovaUnion = pouroshovaUnionSelect.options[pouroshovaUnionSelect.selectedIndex].text;
        const ward = wardSelect.options[wardSelect.selectedIndex].text;

        if (query) {
            // For now, show a placeholder message
            let locationInfo = '';
            if (upazilla && upazilla !== 'Select Upazilla') {
                locationInfo += ` in ${upazilla}`;
                if (pouroshovaUnion && pouroshovaUnion !== 'Select Pouroshova/Union') {
                    locationInfo += `, ${pouroshovaUnion}`;
                    if (ward && ward !== 'Select Ward') {
                        locationInfo += `, ${ward}`;
                    }
                }
            }

            resultsDiv.innerHTML = `
                <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg">
                    <p class="text-green-800 dark:text-green-200">Searching for: <strong>${query}</strong>${locationInfo}</p>
                    <p class="text-sm text-green-600 dark:text-green-300 mt-2">Backend integration needed for actual voter data search.</p>
                </div>
            `;
            resultsDiv.classList.remove('hidden');
        } else {
            resultsDiv.classList.add('hidden');
        }
    }

    searchButton.addEventListener('click', performSearch);

    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });
});
