import React, { useState, useEffect } from "react";
import ServiceCard from "../Components/ServiceCard";
import OrderForm from "../Components/OrderForm";
import KonektemWorks from "../Components/KonektemWorks";
import "../css/services.css";

function ServicesPreview({services, setMakeOrder, setCurrentServiceId}){
    return (
    <div className="min-h-screen px-4 py-10">
        <div className="section-title">
            <h1 className="text-3xl md:text-5xl font-bold text-center mb-10">
                Eksplore Sevis
            </h1>
        </div>
        {services.length === 0 ?
        <div className="spinner-container">
            <div className="spinner"></div>
        </div> :
        <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 max-w-7xl mx-auto">
            {services.map((service) => (
                <ServiceCard key={service.id} service={service} setMakeOrder={setMakeOrder} setCurrentServiceId={setCurrentServiceId}/>
            ))}
        </div>
        }
        <KonektemWorks />
    </div>
    );
}

export default function ServicesPage(){
    const [services, setServices] = useState([]);
    const [makeOrder, setMakeOrder] = useState(false);
    const [currentServiceId, setCurrentServiceId] = useState(null);

    useEffect(() => {
        const load = async () => {
            try {
                let response = await fetch('/konektem/api/v1/services/');
                let json = await response.json();

                if(json.success){
                    setServices(json.data.services);
                } else {
                    console.log('failed to get services from api');
                }
            } catch(err){
                console.log(err.message);
            }
        };
        load();
    }, []);

    return (
    <>
    {makeOrder ? 
    <OrderForm service={services.find(service => Number(service.id) === Number(currentServiceId))}
                setMakeOrder={setMakeOrder}/>:
    <ServicesPreview services={services} setMakeOrder={setMakeOrder} setCurrentServiceId={setCurrentServiceId}/>
    }
    </>
    );
}