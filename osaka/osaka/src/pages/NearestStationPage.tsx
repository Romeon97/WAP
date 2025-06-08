import React, { useState } from "react";
import axios from "axios";

const NearestStationPage: React.FC = () => {
    const [location, setLocation] = useState("");
    const [result, setResult] = useState<any | null>(null);
    const [error, setError] = useState("");

    const handleSearch = async () => {
        try {
            const res = await axios.get("http://127.0.0.1:8000/api/contracten/OSAK1/neareststation", {
                params: { location },
            });
            setResult(res.data);
            setError("");
        } catch {
            setError("Geen station gevonden");
            setResult(null);
        }
    };

    return (
        <div style={{ margin: "2rem" }}>
            <h1>Zoek dichtstbijzijnde station</h1>
            <input
                type="text"
                placeholder="Locatie"
                value={location}
                onChange={(e) => setLocation(e.target.value)}
                style={{ marginRight: "0.5rem", padding: "0.5rem" }}
            />
            <button onClick={handleSearch} style={{ padding: "0.5rem 1rem" }}>
                Zoek
            </button>
            {error && <p style={{ color: "red" }}>{error}</p>}
            {result && (
                <div style={{ marginTop: "1rem" }}>
                    <h2>Station: {result.station.name}</h2>
                    <p>Locatie: {result.nearestLocation.name}</p>
                    <p>Latitude: {result.nearestLocation.latitude}</p>
                    <p>Longitude: {result.nearestLocation.longitude}</p>
                </div>
            )}
        </div>
    );
};

export default NearestStationPage;

