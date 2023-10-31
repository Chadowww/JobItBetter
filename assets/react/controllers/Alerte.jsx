import React, {useEffect, useState} from 'react';
import PropTypes from "prop-types";

function Alerte(props) {
    const [alertes, setAlertes] = React.useState(props.alertes);

    useEffect(() => {
        setAlertes(props.alertes);
    }, [props.alertes]);


    const notifManagment = () =>
    {
        let iconNotification = document.getElementById('icon-notification');
        let iconNotificationBtn = document.getElementById('icon-notification-button');
        let bodyAlerts = document.querySelector('.offcanvas-body');
        let linkAlerts = bodyAlerts.querySelectorAll('.fw-bold.link-alert');
        if (linkAlerts.length > 1) {
            iconNotification.classList.remove('d-none');
            iconNotificationBtn.classList.remove('d-none');
        } else {
            iconNotification.classList.add('d-none');
            iconNotificationBtn.classList.add('d-none');
        }
    }
    notifManagment();

    const readAlert = async (event) => {
        event.preventDefault()
        const readlink = event.currentTarget;
        const link = readlink.getAttribute('href');
        try {
            fetch(link)
                .then(res => res.json())
                .then(data => {
                    const alertIcon = readlink;
                    if (data.state === false) {
                        const eyeIcon = document.getElementById('eye-' + data.id);
                        eyeIcon.classList.add("bi-eye-slash");
                        eyeIcon.classList.remove("bi-eye-fill");
                        alertIcon.classList.remove("fw-bold");
                        alertIcon.classList.add("fw-light");
                    } else {
                        alertIcon.classList.remove("fw-light");
                        alertIcon.classList.add("fw-bold");
                    }
                    notifManagment();
                });
        } catch (error) {
            console.log(error);
        }
    }

    const deleteAlert = async (event)=>{
        event.preventDefault()
        const deleteLink = event.currentTarget;
        const link = deleteLink.getAttribute('href');
        try {
            fetch(link)
                .then(res => res.json())
                .then(data => {
                    console.log(data)
                    if (data.success === true) {
                        setAlertes(alertes.filter(alerte => alerte.id !== data.id));
                    }
                    notifManagment();
                });
        } catch (error) {
            console.log(error);
        }
    }



    return (
        <div className="card be-card-alerte" style={{width: "100%", minHeight: "13.75rem"}} >
            <div className="card-header be-alert fw-bold">
                Mes Alertes
            </div>
            <div className="card-body">
                {
                    alertes.map((alerte) => {
                        return (
                            <div id={'alerte-' + alerte.id} className="d-flex w-100 px-3 justify-content-around" key={alerte.id}>
                                <i  id={'eye-' + alerte.id}  className={alerte.readed === true ? 'bi-eye-fill' : 'bi-eye-slash'}></i>
                                <a id={'alerte-edit-' + alerte.id}
                                   href={'https://127.0.0.1:8000/' + alerte.id + '/alert/'}
                                   className={alerte.readed === true ? 'link-alert text-center fw-bold' : 'link-alert text-center fw-fill'}
                                   onClick={(event)=> readAlert(event)}
                                >
                                    {alerte.applicant + ' ' + alerte.company + alerte.content} {alerte.readed === true ? '!' : ''}
                                </a>
                                <a id={'alerte-delete-' + alerte.id} href={'https://127.0.0.1:8000/' + alerte.id + '/alert/delete'}
                                   className="text-danger"
                                   onClick={(event)=> deleteAlert(event)}
                                >
                                    <i className="fas fa-trash-alt"></i>
                                </a>
                            </div>
                        )
                    })
                }

                <button type="button" className="be-secondary-button m-auto " data-toggle="modal" data-target="#exampleModal">
                    Créer une alerte
                </button>
            </div>
        </div>
    );
}

Alerte.prototypes = {
    alertes: PropTypes.array.isRequired,
}
export default Alerte;

