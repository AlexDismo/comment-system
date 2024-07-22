FROM node:lts-alpine

RUN npm install -g http-server

WORKDIR /app

COPY package*.json ./

RUN npm install


COPY . .

RUN npm install -g @vue/cli
RUN npm run build

VOLUME ["/app/dist"]

EXPOSE 8080
CMD [ "http-server", "dist" ]