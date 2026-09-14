import React, { useEffect, useState } from "react";

function AlertModal({message, type, setShow}) {
    const [countDown, setCountDown] = useState(5);
    const [countDownComplete, setCountDownComplete] = useState(false);
    
    // Auto fermeture après 5 secondes
    
    useEffect(()=>{
        let timer = null;
        if(countDownComplete){
            setShow({});
        } else {
            timer = setInterval(()=>{
                setCountDown(prev => {
                    let newValue = prev - 1;
                    if(newValue === 0) setCountDownComplete(true);
                    return newValue;
                });
            }, 1000);
        }
        
        return () => {timer && clearInterval(timer)}
    }, [countDownComplete]);

    return (
        <div className={`alert alert-${type} alert-dismissible fade show alert-dynamic`}>
            
            <div className="count-down" style={{width: '25px', height: '25px', borderRadius: '50%', border: '1px solid inherit'}}>
                <span>{countDown}</span>
            </div>
            <div className="alert-message-box">
                {message}
                <button type="button" className="btn-close" data-bs-dismiss="alert" onClick={()=>{setCountDownComplete(true)}}></button>
            </div>
        </div>
    );
}

export default AlertModal;