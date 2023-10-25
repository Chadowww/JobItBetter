import React from 'react';
import PropTypes from "prop-types";

function App({categories, technologies }) {

    return (
        <div className="form-accordion-parent row d-flex justify-content-around">
            <form className="form-accordion accordion p-0 position-relative col-3" id="accordionExample">
                <div className="be-joboffer-card-template  sticky-element ">
                    <h2 className=" py-4 fs-5 m-0 w-100 d-flex justify-content-center bg-white be-h2 ">Affinez votre <span>&nbsp recherche</span></h2>
                    {
                        categories.map((category) => {
                            return (
                                <div className="accordion-item" key={category.id}>
                                    <h2 className="accordion-header" id={'heading' + category.id}>
                                        <button
                                            className="accordion-button p-3 fw-bold"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target={'#collapse' + category.id}
                                            aria-expanded="false"
                                            aria-controls={'collapse' + category.id}
                                        >{category.name}</button>
                                    </h2>
                                    <div
                                        className="accordion-collapse collapse"
                                        id= {'collapse' + category.id}
                                    >
                                        <div className="accordion-body d-flex flex-wrap justify-content-between p-0">
                                            {technologies.map((technology) => {
                                                return (
                                                    category.name === technology.category ? (
                                                        <div className="checkbox d-flex justify-content-between col-5 p-2" key={technology.id}>
                                                            <input id={'technology' + technology.id} type="checkbox" value={technology.id} />
                                                            <label htmlFor={'technology' + technology.id} className={'d-flex' +
                                                                ' justify-content-evenly fw-bolder'}>
                                                                {technology.name}
                                                            </label>
                                                        </div>
                                                    ) : null
                                                )
                                            })}
                                        </div>
                                    </div>
                                </div>
                            )
                        })
                    }
                    <div className={'w-100 d-flex justify-content-center'}>
                        <button type="submit" className={'be-primary-button m-3'}>Filtrer</button>
                    </div>
                </div>
            </form>
        </div>
    )
}

App.proptypes = {
    categories: PropTypes.array.isRequired,
    technologies: PropTypes.array.isRequired,
}
export default App;
