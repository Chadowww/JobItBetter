import React from 'react';
import PropTypes from "prop-types";

function Alerte(alertes) {
    return (
        <div className="card be-card-alerte" style={{width: "100%", minHeight: "13.75rem"}} >
            <div className="card-header be-alert fw-bold">
                Mes Alertes
            </div>
            <div className="card-body">
                {
                    alertes.alertes.map((alerte) => {
                        return (
                            <div id={'alerte-' + alerte.id} className="d-flex w-100 px-3 justify-content-around" key={alerte.id}>
                                <i  id={'eye-' + alerte.id}  className={alerte.readed === true ? 'bi-eye-fill' : 'bi-eye-slash'}></i>
                                <a id={'alerte-edit-' + alerte.id} href="" className={alerte.readed === true ? 'link-alert text-center fw-bold' : 'link-alert text-center fw-fill'} onClick={()=> readAlert(event)}
                                >
                                    {alerte.applicant + ' ' + alerte.company + alerte.content} {alerte.readed === true ? '!' : ''}
                                </a>
                                <a id="alerte-delete-{{ alert.id }}" href="" className="text-danger" onClick={()=> deleteAlert(event)}
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

Alerte.prototype = {
    alertes: PropTypes.array.isRequired,
}
export default Alerte;

