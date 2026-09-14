import React from "react";

export default function ServiceCard({service, setMakeOrder, setCurrentServiceId}){
    const orderService = () => {
        setCurrentServiceId(Number(service.id));
        setMakeOrder(true);
    }
    return (
    <div className="bg-white rounded-2xl shadow hover:shadow-lg transition-all duration-300 overflow-hidden">
        <div className="service-img-cont">
            <img className="service-img w-full object-cover"
                src={service.image_location ?? service.cover_image_url}
                alt="Brand Identity"/>
        </div>
        <div className="p-5">
        <h2 className="text-lg font-semibold mb-1">{service.name}</h2>
        <p className="text-sm text-purple-600 mb-2">By ...</p>

        <div className="flex justify-between items-center mt-4">
            <span className="text-xl font-bold text-purple-600">$</span>
            <button type="button" onClick={orderService}
                className="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                Make an order
            </button>
        </div>
        </div>
    </div>
    );
}