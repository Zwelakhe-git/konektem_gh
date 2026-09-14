import React, { useState, useEffect } from "react";

export default function OrderForm({service, setMakeOrder}){
    const [fullName, setFullName] = useState('');
    const [phone, setPhone] = useState('');
    const [email, setEmail] = useState('');
    const [message, setMessage] = useState('');
    const [privacyConsent, setPrivacyConsent] = useState(false);
    const [contactConsent, setContactConsent] = useState(false);
    const [settings, setSettings] = useState([]);
    const [sendfingForm, setSendingForm] = useState(false);
    
    useEffect(()=>{
        const load = async ()=>{
            try{
                let response = await fetch('/konektem/api/v1/site-settings/all');
                let json = response.json();

                if(json.success){
                    console.log(json.data.settings)
                    setSettings(json.data.settings);
                } else {
                    console.error("Failed to fetch settings", json.message);
                }
            } catch(err){
                console.error(err.message);
            }
        }

        load();
    }, []);
    /*const submitForm = async () => {
        const formData = new FormData();
        formData.append('first_name', fullName);
        formData.append('phone', phone);
        formData.append('email', email);
        formData.append('message', message);
        formData.append('privacy_policy', privacyConsent);
        formData.append('contact_agree', contactConsent);
        formData.append('total_amount', service.price);

        try {
            const response = await fetch('/php/dbReader.php?q=createOrder', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();
            
            if (result.success) {
                showMessage(result.message, 'success');
                setTimeout(() => {
                    form.reset();
                    // Перенаправление на страницу благодарности или услуг
                    window.location.href = '/?p=payments&f=service&id=' + serviceData.id + '&orderid=' + result.order_id;
                }, 5000);
            } else {
                showMessage(result.message || 'There was an server error when sending the form. please try again later');
            }
        } catch (error) {
            console.error('Error:', error);
            showMessage('An error occured. please try again');
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }*/
    
    return (
    <>
    <div className="section">
        <div className="section-">
            <h1 className="section-title">Make an order</h1>
        </div>
        <div className="section-nav">
            <button type="button" className="btn btn-primary bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition" onClick={() => {setMakeOrder(false)}}>Back</button>
        </div>
        <div className="min-h-screen bg-gradient-to-br from-blue-50 to-purple-50 py-8">
            <div className="max-w-3xl mx-auto px-4">
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div className="lg:col-span-2" >
                        <div className="bg-white rounded-2xl shadow-xl p-8 form-section-custom" style={{padding: '20px'}}>
                            <div className="flex items-center mb-6">
                                <div className="bg-purple-100 p-3 rounded-lg mr-4">
                                    <i className="fas fa-concierge-bell text-purple-600 text-2xl"></i>
                                </div>
                                <div>
                                    <h2 className="text-2xl font-bold text-gray-800">Service Order</h2>
                                    <p className="text-gray-600">Заполните форму и мы свяжемся с вами</p>
                                </div>
                            </div>

                            <form id="serviceOrderForm" className="space-y-6">
                                <input type="hidden" name="service_id" value={service.id}/>
                                
                                <div className="bg-blue-50 rounded-xl p-4 mb-6">
                                    <div className="flex items-start space-x-4">
                                        {service.image_location ?
                                        <img src={service.image_location} 
                                            alt="image" 
                                            className="w-20 h-20 rounded-lg object-cover shadow"/>
                                        :
                                        <div className="w-20 h-20 bg-purple-100 rounded-lg flex items-center justify-center">
                                            <i className="fas fa-concierge-bell text-purple-600 text-2xl"></i>
                                        </div>
                                        }
                                        <div className="flex-1">
                                            <h3 className="font-semibold text-lg text-gray-800">{service.name}</h3>
                                            <p className="text-gray-600 text-sm mt-1 line-clamp-2">{service.description}</p>
                                            {service.price > 0 &&
                                            <div className="mt-2">
                                                <span className="text-2xl font-bold text-purple-600">${service.price}</span>
                                                <span className="text-gray-500 text-sm ml-2">от</span>
                                            </div>
                                            }
                                        </div>
                                    </div>
                                </div>

                                <div className="form-group-custom">
                                    <h3 className="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                        <i className="fas fa-user-circle text-purple-600 mr-2"></i>
                                        Contact information
                                    </h3>
                                    
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label htmlFor="full_name" className="block text-sm font-medium text-gray-700 mb-1">
                                                Surname Name *
                                            </label>
                                            <input type="text" id="full_name" name="full_name" required
                                                className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 form-input-custom"
                                                placeholder="Surname Middlename Lastname" onChange={(e)=>{setFullName(e.target.value)}}/>
                                        </div>
                                        <div>
                                            <label htmlFor="phone" className="block text-sm font-medium text-gray-700 mb-1">
                                                Phone *
                                            </label>
                                            <input type="tel" id="phone" name="phone" required
                                                className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 form-input-custom"
                                                placeholder="phone number" onChange={(e)=>{setPhone(e.target.value)}}/>
                                        </div>
                                    </div>
                                    
                                    <div className="mt-4">
                                        <label htmlFor="email" className="block text-sm font-medium text-gray-700 mb-1">
                                            Email *
                                        </label>
                                        <input type="email" id="email" name="email" required
                                            className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 form-input-custom"
                                            placeholder="example@email.com" onChange={(e)=>{setEmail(e.target.value)}}/>
                                    </div>
                                </div>

                                <div className="form-group-custom">
                                    <label htmlFor="message" className="block text-sm font-medium text-gray-700 mb-1">
                                        Additional Information
                                        <span className="text-gray-400 text-xs font-normal ml-1">(mandatory)</span>
                                    </label>
                                    <textarea id="message" name="message" rows="4"
                                            className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 form-input-custom resize-none"
                                            placeholder="Enter more details about the order..."></textarea>
                                </div>

                                <div className="form-group-custom">
                                    <div className="space-y-3">
                                        <label className="flex items-start space-x-3">
                                            <input type="checkbox" name="privacy_policy" required
                                                className="mt-1 w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500" onChange={(e)=>{setPrivacyConsent(e.target.checked)}}/>
                                            <span className="text-sm text-gray-600">
                                                Agree to processing of personal information 
                                                <a href="/privacy" className="text-purple-600 hover:underline">Confidentiality Policy</a>
                                            </span>
                                        </label>
                                        <label className="flex items-start space-x-3">
                                            <input type="checkbox" name="contact_agree" required
                                                className="mt-1 w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500" onChange={(e)=>{setContactConsent(e.target.value)}}/>
                                            <span className="text-sm text-gray-600">
                                                Agree to receive notifications through these contacts
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" 
                                        className="w-full bg-gradient-to-r from-purple-600 to-blue-600 text-white py-4 px-6 rounded-lg font-semibold text-lg hover:from-purple-700 hover:to-blue-700 transition duration-200 flex items-center justify-center payment-btn-custom shadow-lg hover:shadow-xl">
                                    <i className="fas fa-paper-plane mr-3"></i>
                                    Send order
                                </button>

                                <div id="formMessage" className="hidden"></div>
                            </form>
                        </div>
                    </div>

                    <div className="lg:col-span-1" style={{padding: '20px'}}>
                        <div className="bg-white rounded-2xl shadow-xl p-6 sticky top-6 sidebar-enhanced"
                        style={{padding: "20px",
                        margin: "0px auto"
                        }}
                        >
                            <h3 className="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i className="fas fa-info-circle text-blue-600 mr-2"></i>
                                How does it work?
                            </h3>
                            
                            <div className="space-y-4 mb-6">
                                <div className="flex items-start space-x-3">
                                    <div className="bg-blue-100 text-blue-600 rounded-full w-8 h-8 flex items-center justify-center flex-shrink-0">
                                        <span className="font-bold">1</span>
                                    </div>
                                    <div>
                                        <p className="font-medium text-gray-800">Fill in the form</p>
                                        <p className="text-sm text-gray-600">Enter your contact information and order details</p>
                                    </div>
                                </div>
                                
                                <div className="flex items-start space-x-3">
                                    <div className="bg-blue-100 text-blue-600 rounded-full w-8 h-8 flex items-center justify-center flex-shrink-0">
                                        <span className="font-bold">2</span>
                                    </div>
                                    <div>
                                        <p className="font-medium text-gray-800">We contact with you</p>
                                        <p className="text-sm text-gray-600">within 1-2 hours</p>
                                    </div>
                                </div>
                                
                                <div className="flex items-start space-x-3">
                                    <div className="bg-blue-100 text-blue-600 rounded-full w-8 h-8 flex items-center justify-center flex-shrink-0">
                                        <span className="font-bold">3</span>
                                    </div>
                                    <div>
                                        <p className="font-medium text-gray-800">We discuss details of the order</p>
                                        <p className="text-sm text-gray-600">date, cost and requirements</p>
                                    </div>
                                </div>
                                
                                <div className="flex items-start space-x-3">
                                    <div className="bg-blue-100 text-blue-600 rounded-full w-8 h-8 flex items-center justify-center flex-shrink-0">
                                        <span className="font-bold">4</span>
                                    </div>
                                    <div>
                                        <p className="font-medium text-gray-800">We complete the job</p>
                                        <p className="text-sm text-gray-600"></p>
                                    </div>
                                </div>
                            </div>

                            <div className="border-t pt-4">
                                <h4 className="font-semibold text-gray-800 mb-3">contacts for the questions</h4>
                                <div className="space-y-2">
                                    <div className="flex items-center text-sm text-gray-600">
                                        <i className="fas fa-phone text-purple-600 mr-2 w-4"></i>
                                        <span>{settings.contact?.phone}</span>
                                    </div>
                                    <div className="flex items-center text-sm text-gray-600">
                                        <i className="fas fa-envelope text-purple-600 mr-2 w-4"></i>
                                        <span>{settings.email?.site_email}</span>
                                    </div>
                                    <div className="flex items-center text-sm text-gray-600">
                                        <i className="fas fa-clock text-purple-600 mr-2 w-4"></i>
                                        <span>Mon-Fri</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </>
    );
}

