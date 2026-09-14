function interviewsSection(interviewsData) {
    let interviewsList = '';
    for(let index = 0; index < interviewsData.interviews.length; ++index){
        let highlightsList = '';
        var description = interviewsData.interviews[index].description;
        description = description == false ? "" : description;
        interviewsList += `
        <div class="interview-card">
            <div class="iv-container">
                <img src="${interviewsData.interviews[index].image_location}" alt="${interviewsData.interviews[index].personName}" class="interview-image" />
                <div class="interview-content">
                    <div class="person-info">
                        <h3 class="person-name">${interviewsData.interviews[index].personName ?? "unknown guest"}</h3>
                        <p class="person-title">${interviewsData.interviews[index].personTitle ?? ""}</p>
                        <div class="interview-meta">
                            <span class="date">
                                <i class="fas fa-calendar"></i>
                                ${interviewsData.interviews[index].interviewDate ?? 'date'}
                            </span>
                            <span class="read-time">
                                <i class="fas fa-clock"></i>
                                ${interviewsData.interviews[index].interviewLength ?? ""}
                            </span>
                        </div>
                    </div>
                    <div class="interview-description">${description}</div>
                    <a style="display: inline-block;
                        color: #2b6cb0;
                        text-decoration: none;
                        font-weight: 500;
                        cursor: pointer;
                        margin: 8px 0px;
                        font-size: 14px;" href="/?p=interviews&id=${interviewsData.interviews[index].id}">
                        read more
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                    <div class="interview-actions">
                        <a href="/?p=interviews&id=${interviewsData.interviews[index].id}" class="watch-btn">
                            <i class="fas fa-play"></i>
                            Gade Entèvyou
                        </a>
                        <button class="share-btn">
                            <i class="fas fa-share-alt"></i>
                            Pataje
                        </button>
                    </div>
                </div>
            </div>
        </div>`;
    }
    
    return `
    <div id="interviews-section" class="margin-rl-1 col container-outer pad-20 white-bg bdr-box">
        <div id="interviews-section-bg" class="full-h"></div>
        <div class="section-info" style="text-align: center;margin-bottom:15px;">
            <h2>${interviewsData.pageTitle.toUpperCase()}</h2>
        </div>
        <div id="interviews-list" class="container-inner" style="display: flex; justify-content: space-evenly; flex-wrap: wrap">
            ${interviewsList}
        </div>
        <a href="/?p=more-interviews">
            <div class="more-actions">
                <span>Wè Plis Entèvyou</span>
                <ion-icon name="arrow-forward-outline"></ion-icon>
            </div>
        </a>
    </div>`;
}

export {interviewsSection};