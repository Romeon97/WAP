import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import LoginPage from "./pages/LoginPage";
import AdminPage from "./pages/AdminPage";
import HomePage from "./pages/HomePage";
import NearestStationPage from "./pages/NearestStationPage";
import CompaniesPage from "./pages/CompaniesPage";

function App() {
    return (
        <Router>
            <Routes>
                <Route path="/" element={<LoginPage />} />
                <Route path="/admin" element={<AdminPage />} />
                <Route path="/home" element={<HomePage />} />
                <Route path="/nearest" element={<NearestStationPage />} />
                <Route path="/companies" element={<CompaniesPage />} />
            </Routes>
        </Router>
    );
}

export default App;