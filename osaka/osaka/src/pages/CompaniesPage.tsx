import { useEffect, useState } from "react";
import axios from "axios";

interface Company {
    id: number;
    name: string;
    street?: string;
    number?: number;
    zip_code?: string;
    city?: string;
    country?: string;
    email?: string;
}

function CompaniesPage() {
    const [companies, setCompanies] = useState<Company[]>([]);

    useEffect(() => {
        axios.get<Company[]>("http://127.0.0.1:8000/api/companies").then(res => {
            setCompanies(res.data);
        });
    }, []);

    return (
        <div>
            <h1>Companies</h1>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Street</th>
                        <th>Number</th>
                        <th>Zip code</th>
                        <th>City</th>
                        <th>Country</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    {companies.map((c) => (
                        <tr key={c.id}>
                            <td>{c.name}</td>
                            <td>{c.street}</td>
                            <td>{c.number}</td>
                            <td>{c.zip_code}</td>
                            <td>{c.city}</td>
                            <td>{c.country}</td>
                            <td>{c.email}</td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
}

export default CompaniesPage;
