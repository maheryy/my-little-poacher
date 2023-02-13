import "@testing-library/jest-dom";
import server from "./mocks/server";
import { cleanup } from "@testing-library/vue";

beforeAll(() => server.listen());
afterEach(() => server.resetHandlers());
afterAll(() => server.close());
afterEach(cleanup);
