import React from "react";
import styles from "../styles/HomePage.module.css";

const HomePage: React.FC = () => {
    const handleNavigate = (path: string) => {
        window.location.href = path;
    };

    return (
        <div className={styles.container}>
            <div className={styles.buttonGroup}>
                <button
                    className={styles.dataButton}
                    onClick={() => handleNavigate("/regen")}
                >
                    Regen&nbsp;data
                </button>

                <button
                    className={styles.dataButton}
                    onClick={() => handleNavigate("/wind")}
                >
                    Wind&nbsp;data
                </button>
                <button
                    className={styles.dataButton}
                    onClick={() => handleNavigate("/nearest")}
                >
                    Dichtstbijzijnde&nbsp;station
                </button>
            </div>
        </div>
    );
};

export default HomePage;
