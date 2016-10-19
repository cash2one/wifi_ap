// 由于大多数shape默认的isCover都是相同的逻辑
// 所以在echarts里临时抽象一个module，用于isCover method

define(function () {
    return function (x, y) {
        var originPos = this.transformCoordToLocal(x, y);
        x = originPos[0];
        y = originPos[1];

        return this.isCoverRect(x, y);
    };
});
