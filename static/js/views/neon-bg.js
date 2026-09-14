
class NeonBackground{
    constructor(lineCount){
        this.lineCount = lineCount;
        this.#createBackground();
    }
    #createBackground(){
        let neonLines = "";
        for(let i = 0; i < this.lineCount; ++i){
            neonLines += `<div class="neon-line"
            style="top;${(i + 1) > 0 && 100 / (i+1)}%;animation-delay;${i}s"></div>`
        }
        this.bgHtml =  `<div class="neon-bg">
                <!-- Neon animated lines -->
                ${neonLines}
            </div>`;
    }
    render(){
        //
    }
}

export function RenderNeonBackground(){
    let bg = new NeonBackground(300);
    return bg.bgHtml;
}