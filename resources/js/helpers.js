
export function addNewFilter(existingData, newData) {
    if (isEmpty(newData)) return existingData;
    if (isEmpty(newData[0])) return existingData;
    if (isEmpty(newData[0].id)) return existingData;
  
    const newObjects = newData.filter(
      ({ id: id1 }) => !existingData.some(({ id: id2 }) => id2 === id1)
    );
  
    existingData = existingData.map(
      (ed) => {
        const _ed = newData.find((j) => j.id == ed.id);
        if (!isEmpty(_ed)) {
          return _ed
        }
        return ed
      }
    );
    return existingData.concat(newObjects);
  }
  
  export function addUpdateItem(existingData, newData) {
    if (isEmpty(newData)) return existingData;
    if (isEmpty(newData.id)) return existingData;
    const data = existingData.filter((i) => i.id !== newData.id);
    data.push(newData);
    return data;
  }
  
  export function dcdLrvlValdtnErr(Errs) {
    if(isEmpty(Errs)) return false;
    const errors = [];
    if (typeof Errs !== "object") return false;
    if (Object.keys(Errs).length === 0) return false;
    for (const key in Errs) {
      if (Errs[key].length > 0) {
        for (const ek in Errs[key]) {
          errors.push(Errs[key][ek]);
        }
      }
    }
    return errors.length > 0 ? errors : false;
  }
  
  export function isEmpty(value) {
    if (typeof value === typeof undefined) return true;
    if (value === undefined) return true;
    if (value === "undefined") return true;
    if (value === null) return true;
    if (value === "null") return true;
    if (value === false) return true;
    if (value === 0) return true;
    if (value === "0") return true;
    if (typeof value === "object" && Object.keys(value).length === 0) return true;
    if (typeof value === "array" && value.length === 0) return true;
    if (typeof value === "string" && value.trim().length === 0) return true;
    return false;
  }
  
  export function onlyUnique(value, index, self) {
    return self.indexOf(value) === index;
  }
  
  export function isNumeric(value = null) {
    if (isEmpty(value)) return false;
    return !isNaN(parseInt(value));
  }
  
  export function strToLower(value = null, makeSlug = false) {
    if (typeof value !== "string") return value;
    if (isEmpty(value)) return null;
    if (makeSlug) return value.toLowerCase().replace(/\s+/g, "-");
    return value.toLowerCase();
  }
  
  export function strToUpper(value = null) {
    if (typeof value !== "string") return value;
    if (isEmpty(value)) return null;
    return value.toUpperCase();
  }
  
  export function _nl2Br(value) {
    return value.replace(/\n/g, "<br />");
  }
  
  export function _limitTo(value, length) {
    if (!value) return "";
    if (value.length > length) return value.substring(0, length) + "...";
    return value;
  }
  
  export function _stripHtml(string) {
    return string.replace(/<\/?[^>]+>/gi, " ");
  }
  
  export function makeFormDataFromObject(input) {
    const data = new FormData();
    Object.keys(input).forEach((k, index) => {
      let type = typeof input[k];
      type = Array.isArray(input[k])
        ? "array"
        : Object.prototype.toString.call(input[k]) === "[object Object]"
          ? "object"
          : Object.prototype.toString.call(input[k]) === "[object Null]"
            ? "null"
            : type;
      switch (type) {
        case "object": {
          if (
            input[k].src &&
            typeof input[k].src === "string" &&
            input[k].src.startsWith("blob")
          ) {
            data.append(k, input[k]);
          } else {
            data.append(k, JSON.stringify(input[k]));
          }
          break;
        }
        case "array": {
          input[k].forEach((value) => {
            data.append(`${k}[]`, value);
          });
          break;
        }
        case "null": {
          data.append(k, "");
          break;
        }
        default: {
          const appendVal = typeof input[k] !== "undefined" ? input[k] : null;
          if (appendVal !== null) data.append(k, appendVal);
        }
      }
    });
    return data;
  }
  
  export function checkObjIsSame(source, target) {
    if (isEmpty(target)) return false;
    const sourceKeys = Object.keys(source);
    return !sourceKeys.some((i) => {
      if (isEmpty(target[i])) return true;
      return source[i] != target[i];
    });
  }
  
  export function checkAllRequired(requiredFields, formObj) {
    if (isEmpty(requiredFields)) return false;
    if (isEmpty(formObj)) return false;
    return !requiredFields.some((i) => {
      return isEmpty(formObj[i]);
    });
  }