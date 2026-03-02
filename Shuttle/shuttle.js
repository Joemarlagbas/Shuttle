let map = L.map("map").setView([14.5995, 120.9842], 13);
L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(map);

const latEl = document.getElementById("lat");
const lngEl = document.getElementById("lng");
const placeEl = document.getElementById("place");
const lastUpdateEl = document.getElementById("lastUpdate");
const driverEl = document.getElementById("driver");
const select = document.getElementById("shuttleSelect");

const markers = {}; // markers by shuttle_id

// Fetch shuttle locations and driver info
async function loadShuttles() {
    try {
        const res = await fetch("get_location.php");
        const shuttles = await res.json();

        // Clear existing markers before adding new ones
        Object.values(markers).forEach(marker => marker.remove());

        shuttles.forEach(shuttle => {
            const lat = parseFloat(shuttle.latitude) || 14.5995;
            const lng = parseFloat(shuttle.longitude) || 120.9842;

            if (!markers[shuttle.shuttle_id]) {
                const marker = L.marker([lat, lng]).addTo(map);
                marker.bindPopup(`🚍 ${shuttle.shuttle_name}`);
                marker.on("click", () => showDriverInfo(shuttle, true));
                markers[shuttle.shuttle_id] = marker;
            } else {
                markers[shuttle.shuttle_id].setLatLng([lat, lng]);
            }
        });
    } catch (e) {
        console.error("Error loading shuttles:", e);
    }
}

// Show driver info & coordinates
async function showDriverInfo(shuttle, pan = false) {
    driverEl.textContent = "Driver: " + (shuttle.driver_name || "--");

    const lat = parseFloat(shuttle.latitude);
    const lng = parseFloat(shuttle.longitude);

    latEl.textContent = lat.toFixed(5);
    lngEl.textContent = lng.toFixed(5);
    lastUpdateEl.textContent = new Date().toLocaleTimeString();

    if (lat && lng) {
        if (pan) map.setView([lat, lng], 15);
        try {
            const r = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`);
            const data = await r.json();
            placeEl.textContent = data.display_name || "--";
        } catch (e) {
            placeEl.textContent = "--";
        }
    }

    document.getElementById("status").textContent = `🚍 ${shuttle.shuttle_name} info`;
}

// Update side panel on shuttle select
select.addEventListener("change", async () => {
    const opt = select.selectedOptions[0];
    if (!opt.value) return;

    const shuttle_id = opt.value;
    try {
        const res = await fetch("get_location.php");
        const shuttles = await res.json();
        const shuttle = shuttles.find(s => s.shuttle_id == shuttle_id);
        if (shuttle) showDriverInfo(shuttle, true);
    } catch (e) {
        console.error(e);
    }
});

// Refresh every 3 seconds
setInterval(loadShuttles, 3000);
loadShuttles();